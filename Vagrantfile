# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/trusty64"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network :forwarded_port, guest: 80, host: 8880
  config.vm.network :forwarded_port, guest: 443, host: 4443

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.22"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
    vb.memory = "2048"
  end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Define a Vagrant Push strategy for pushing to Atlas. Other push strategies
  # such as FTP and Heroku are also available. See the documentation at
  # https://docs.vagrantup.com/v2/push/atlas.html for more information.
  # config.push.define "atlas" do |push|
  #   push.app = "YOUR_ATLAS_USERNAME/YOUR_APPLICATION_NAME"
  # end

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
	config.vm.provision "shell", inline: <<-SHELL
		sudo apt-get update
		sudo apt-get upgrade -y
		
		sudo apt-get install -y git
		
		sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password 123'
		sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 123'
		sudo apt-get -y install mysql-server
		
		sudo apt-get install -y apache2
		#if ! [ -L /var/www/html ]; then
		#	rm -rf /var/www/html
		#	ln -fs /vagrant /var/www/html
		#fi
		sudo apt-get install -y php5 libapache2-mod-php5 php5-mcrypt php5-curl php5-mysql php5-xdebug
		sudo cp /vagrant/vagrant_setup/php/php.ini /etc/php5/apache2/php.ini
		sudo a2enmod rewrite
        sudo rm /etc/apache2/sites-enabled/000-default.conf
		cp /vagrant/vagrant_setup/apache/apache2.conf /etc/apache2/
		sudo service apache2 restart
        cp /vagrant/vagrant_setup/bash/.bash_profile /home/vagrant
	SHELL
    config.vm.provision "vhost", type: "shell", inline: <<-SHELL
		cd /etc/apache2/
        sudo mkdir -p ssl/crt ssl/key
        cd ssl
        sudo openssl req -new -x509 -days 365 -keyout key/vhost1.key -out crt/vhost1.crt -nodes -subj  '/O=VirtualHost Website Company name/OU=Virtual Host Website department/CN=www.virtualhostdomain.com'
        sudo cp /vagrant/vagrant_setup/apache/vhost.conf /etc/apache2/sites-enabled
        sudo a2enmod ssl
		sudo service apache2 restart
	SHELL
    config.vm.provision "composer", type: "shell", env: {"SYMFONY__DATABASE__USER" => "root", "SYMFONY__DATABASE__PASSWORD" => "123", "SYMFONY__GOOGLE__API_KEY" => "AIzaSyAGSmT5XHsCLqvAf5eVRFHJBjxRQz2MGuY"}, inline: <<-SHELL
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        php -r "if (hash_file('SHA384', 'composer-setup.php') === '070854512ef404f16bac87071a6db9fd9721da1684cd4589b1196c3faf71b9a2682e2311b36a5079825e155ac7ce150d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
        php composer-setup.php
        php -r "unlink('composer-setup.php');"
        sudo mv composer.phar /usr/local/bin/composer
        cd /vagrant
        composer install
        php /vagrant/bin/console doctrine:database:create
        php /vagrant/bin/console doctrine:database:create --env=test
        php /vagrant/bin/console doctrine:schema:update --force
        php /vagrant/bin/console doctrine:schema:update --env=test --force
    SHELL
end
