param(
  [string]$SiteRoot = 'C:\Users\Maxiliano\Local Sites\yasofish\app\public',
  [string]$Package = (Join-Path $PSScriptRoot '..\outputs\yasofish-storefront-child-1.2.1.zip'),
  [Parameter(Mandatory=$true)][string]$ExpectedSha256
)
$ErrorActionPreference = 'Stop'
$site = (Resolve-Path -LiteralPath $SiteRoot).Path
if ($site -notmatch '\\Local Sites\\yasofish\\app\\public$' -or !(Test-Path -LiteralPath (Join-Path $site 'wp-load.php'))) { throw 'Solo se permite LocalWP yasofish.' }
$zip = (Resolve-Path -LiteralPath $Package).Path
if ((Get-FileHash -LiteralPath $zip -Algorithm SHA256).Hash -ne $ExpectedSha256) { throw 'SHA256 incorrecto.' }
$destination = Join-Path $site 'wp-content\themes\yasofish-storefront-child'
$backupRoot = Join-Path $PSScriptRoot '..\outputs\local-backups'
New-Item -ItemType Directory -Force -Path $backupRoot | Out-Null
$stamp = Get-Date -Format 'yyyyMMdd-HHmmss-fff'
$backup = Join-Path $backupRoot ($stamp + '.zip')
if (Test-Path -LiteralPath $destination) { Compress-Archive -LiteralPath $destination -DestinationPath $backup }
$stage = Join-Path $backupRoot ($stamp + '-stage')
Expand-Archive -LiteralPath $zip -DestinationPath $stage
$source = Join-Path $stage 'yasofish-storefront-child'
if (!(Test-Path -LiteralPath (Join-Path $source 'style.css'))) { throw 'ZIP no válido.' }
# Overlay only: preserve any local-only files; no database or settings are touched.
New-Item -ItemType Directory -Force -Path $destination | Out-Null
Get-ChildItem -LiteralPath $source | Copy-Item -Destination $destination -Recurse -Force
Write-Output "Tema sincronizado. Respaldo: $backup"
