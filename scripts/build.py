"""Validate source and produce an allowlisted, deterministic WordPress ZIP."""
from pathlib import Path
import hashlib, os, re, shutil, subprocess, zipfile
import xml.etree.ElementTree as ET
ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / 'yasofish-storefront-child'
version = re.search(r'^Version:\s*(\S+)', (THEME/'style.css').read_text(encoding='utf-8-sig'), re.M)[1]
php = os.environ.get('YASOFISH_PHP', 'php')
for path in sorted(THEME.rglob('*.php')):
    subprocess.run([php, '-l', str(path)], check=True)
for path in sorted(THEME.rglob('*.js')):
    subprocess.run(['node', '--check', str(path)], check=True)
assert 'Template: storefront' in (THEME/'style.css').read_text(encoding='utf-8-sig')
assert (THEME/'screenshot.png').is_file(), 'Missing theme preview'
files = sorted(p for p in THEME.rglob('*') if p.is_file() and p.suffix in {'.php','.css','.js','.png','.svg','.md','.txt'})
for p in files:
    if p.suffix == '.svg':
        svg = ET.fromstring(p.read_text(encoding='utf-8-sig'))
        assert svg.tag == '{http://www.w3.org/2000/svg}svg', f'Missing SVG namespace: {p.name}'
        assert not any(e.tag.split('}')[-1] in {'script', 'foreignObject'} or any(k.lower().startswith('on') for k in e.attrib) for e in svg.iter()), f'Active SVG: {p.name}'
    if p.suffix != '.png':
        assert not re.search(r'gh[pousr]_[A-Za-z0-9]{20,}|github_pat_[A-Za-z0-9_]+|-----BEGIN (?:RSA |OPENSSH )?PRIVATE KEY',p.read_text(encoding='utf-8-sig')), f'Potential secret: {p.name}'
out = ROOT/'outputs'
out.mkdir(exist_ok=True)
target = out/f'yasofish-storefront-child-{version}.zip'
with zipfile.ZipFile(target,'w',zipfile.ZIP_DEFLATED) as z:
    for p in files:
        entry = zipfile.ZipInfo(p.relative_to(ROOT).as_posix(), (2026,1,1,0,0,0))
        entry.compress_type = zipfile.ZIP_DEFLATED
        entry.external_attr = 0o100644 << 16
        z.writestr(entry,p.read_bytes())
with zipfile.ZipFile(target) as z:
    assert z.testzip() is None
    assert all(n.startswith('yasofish-storefront-child/') and '..' not in n for n in z.namelist())
digest = hashlib.sha256(target.read_bytes()).hexdigest()
(out/(target.name+'.sha256')).write_text(digest+'  '+target.name+'\n')
print(f'PASS: {len(files)} files, version {version}, SHA256 {digest}')
