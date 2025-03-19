# german-fuel-api-proxy

A secure PHP proxy server for the Tankerkönig API (https://creativecommons.tankerkoenig.de/) with built-in caching, rate-limiting, and API key protection. Ideal for frontend applications like Vue.js without putting unnecessary load on the external API.

## Features

- 🔐 Custom proxy API key protection
- 💾 5-minute cache for identical requests (file-based)
- 🚦 Rate-limiting (default: 30 requests per minute)
- 🌍 CORS-friendly for all frontend technologies (e.g., Vue, React)
- 🛡️ `.htaccess` to protect sensitive files & directories

## Project Structure

```
.
├── api.php
├── config.php              # (created from config-example.php)
├── config-example.php
├── .htaccess
├── /cache/                 # (auto-generated)
└── rate_limit.json         # (auto-generated)
```

## Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/AljoshaVieth/german-fuel-api-proxy.git
   cd german-fuel-api-proxy
   ```

2. Copy and configure the example config:
   ```bash
   cp config-example.php config.php
   ```
   - Add your **Tankerkönig API key** and your **custom proxy key**.

3. Deploy the proxy to a PHP-enabled web server (e.g., Apache/Nginx).

## Usage

### API Endpoint

`/api.php?key=YOUR_PROXY_KEY&lat=LAT&lng=LNG&rad=RADIUS&type=TYPE&sort=SORT`

### Example (Vue.js):

```js
fetch('/api.php?key=my-proxy-key&lat=52.52&lng=13.40&rad=5&type=e5&sort=dist')
  .then(res => res.json())
  .then(data => console.log(data.stations))
  .catch(err => console.error(err));
```

### Available Parameters

| Parameter | Description                         | Example           |
|-----------|-------------------------------------|-------------------|
| `key`   | Your custom proxy key from config   | my-proxy-key      |
| `lat`     | Latitude of the location            | 52.5200           |
| `lng`     | Longitude of the location           | 13.4050           |
| `rad`     | Radius in km (max. 25)              | 5                 |
| `type`    | Fuel type (`e5`, `e10`, `diesel`, `all`) | e5          |
| `sort`    | Sort by `price` or `dist`           | dist              |

## Security & Protections

- Only usable with a valid `key` parameter
- All requests are cached for **5 minutes**
- Global rate-limit of **30 requests per minute** (configurable)
- `.htaccess` blocks access to sensitive files and `.git` directories

## License

MIT License
