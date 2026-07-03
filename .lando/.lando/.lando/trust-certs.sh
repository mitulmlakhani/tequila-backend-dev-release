#!/bin/bash
# =============================================================================
# Script to automate trusting the Lando Local CA certificate on the host machine.
# Run this on your host machine: bash trust-certs.sh
# =============================================================================

OS="$(uname)"

if [ "$OS" = "Darwin" ]; then
  echo "🔒 Detected macOS. Trusting Lando SSL certificate..."
  if [ -f ~/.lando/certs/lndo.site.pem ]; then
    sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ~/.lando/certs/lndo.site.pem
    echo "✅ Successfully trusted the Lando SSL certificate. Please restart your browser."
  else
    echo "❌ Error: Certificate ~/.lando/certs/lndo.site.pem not found!"
  fi
elif [ "$OS" = "Linux" ]; then
  echo "🔒 Detected Linux. Trusting Lando SSL certificate..."
  if [ -f ~/.lando/certs/LandoCA.crt ]; then
    if [ -d /etc/ca-certificates/trust-source/anchors/ ]; then
      sudo cp ~/.lando/certs/LandoCA.crt /etc/ca-certificates/trust-source/anchors/
      sudo trust extract-compat
      echo "✅ Successfully trusted the Lando CA certificate."
    elif [ -d /usr/local/share/ca-certificates/ ]; then
      sudo cp ~/.lando/certs/LandoCA.crt /usr/local/share/ca-certificates/
      sudo update-ca-certificates
      echo "✅ Successfully trusted the Lando CA certificate."
    else
      echo "❌ Error: Unknown CA trust directory for this Linux distribution."
    fi
  elif [ -f /certs/cert.pem ]; then
    echo "🔒 Detected Lando container environment. Trusting certificate..."
    sudo cp /certs/cert.pem /usr/local/share/ca-certificates/lndo.site.crt
    sudo update-ca-certificates
    echo "✅ Successfully trusted Lando SSL certificate inside the container."
  else
    echo "❌ Error: Certificate ~/.lando/certs/LandoCA.crt not found!"
  fi
else
  echo "⚠️ Unsupported OS: $OS. Please trust the certificate manually."
fi
