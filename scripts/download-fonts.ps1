$ErrorActionPreference = 'Stop'

$cssUrl = 'https://fonts.bunny.net/css?family=instrument-sans:400,500,600'
$response = Invoke-WebRequest -UseBasicParsing -Uri $cssUrl
if (-not $response -or -not $response.Content) {
	throw 'Failed to download CSS index from Bunny Fonts.'
}
$css = $response.Content

# Extract .woff2 URLs
$pattern = 'https?://[^)\s]+\.woff2'
$matches = [System.Text.RegularExpressions.Regex]::Matches($css, $pattern)
if ($matches.Count -lt 1) {
	throw 'No .woff2 URLs found in CSS.'
}

$urls = @{}
foreach ($m in $matches) {
	$url = $m.Value
	if ($url -match '400.*?woff2' -and -not $urls.ContainsKey('400')) { $urls['400'] = $url }
	elseif ($url -match '500.*?woff2' -and -not $urls.ContainsKey('500')) { $urls['500'] = $url }
	elseif ($url -match '600.*?woff2' -and -not $urls.ContainsKey('600')) { $urls['600'] = $url }
}

if ($urls.Keys.Count -lt 3) {
	Write-Warning 'Could not find all 400/500/600 URLs. Will try to download whatever was found.'
}

$dest = Join-Path $PSScriptRoot '..' | Resolve-Path | ForEach-Object { Join-Path $_ 'public/fonts' }
New-Item -ItemType Directory -Force -Path $dest | Out-Null

if ($urls.ContainsKey('400')) { Invoke-WebRequest -UseBasicParsing -Uri $urls['400'] -OutFile (Join-Path $dest 'instrument-sans-latin-400-normal.woff2') }
if ($urls.ContainsKey('500')) { Invoke-WebRequest -UseBasicParsing -Uri $urls['500'] -OutFile (Join-Path $dest 'instrument-sans-latin-500-normal.woff2') }
if ($urls.ContainsKey('600')) { Invoke-WebRequest -UseBasicParsing -Uri $urls['600'] -OutFile (Join-Path $dest 'instrument-sans-latin-600-normal.woff2') }

Write-Output "Downloaded fonts to $dest"

