param([Parameter(Mandatory=$true)][ValidatePattern('^\d+\.\d+\.\d+$')][string]$Version)
$ErrorActionPreference = 'Stop'
$out = Join-Path $PSScriptRoot ('..\outputs\release-' + $Version)
New-Item -ItemType Directory -Path $out -Force | Out-Null
# gh authenticates outside the theme. No tokens in source or in WordPress.
gh release download "v$Version" --repo Almostking/yasofishweb --dir $out --pattern '*.zip' --pattern '*.sha256'
if ($LASTEXITCODE -ne 0) { throw 'No se pudo descargar la versión publicada.' }
$name = "yasofish-storefront-child-$Version.zip"
$hash = ((Get-Content -LiteralPath (Join-Path $out ($name + '.sha256')) -Raw).Trim() -split '\s+')[0]
& (Join-Path $PSScriptRoot 'sync-local.ps1') -Package (Join-Path $out $name) -ExpectedSha256 $hash
