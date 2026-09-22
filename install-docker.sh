# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y ca-certificates curl gnupg lsb-release

# Add Docker GPG key
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Add Docker repository
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Add current user to docker group
sudo usermod -aG docker $USER
newgrp docker

# Verify installation
docker --version
docker compose version

#Install Avahi
HOSTNAME="tequila"

if [[ "$(hostnamectl --static)" != "$HOSTNAME" ]]; then
    echo "Setting hostname: $HOSTNAME"
    sudo hostnamectl set-hostname "$HOSTNAME"
fi

echo "Enabling Avahi/mDNS..."

sudo systemctl enable --now avahi-daemon

if ! sudo systemctl is-active --quiet avahi-daemon; then
    echo "Failed to start avahi-daemon."
    exit 1
fi

echo "Avahi is running: ${HOSTNAME}.local"