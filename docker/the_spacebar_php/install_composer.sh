#!/bin/sh

###
# Installs composer
#
installComposer()
{
    # url for composer signature
    local sig_url='https://composer.github.io/installer.sig'
    # file to contain downloaded composer signature
    local sig_file='expected-composer.sig'
    # downloaded signature data
    local sig_data='123'
    # url for composer installer
    local ins_url='https://getcomposer.org/installer'
    # what we call the composer installer
    local ins_file='composer-setup.php'
    # file to contain calculated composer signature
    local hash_file='actual-composer.sig'
    # calculated signature data
    local hash_data='321'

    echo Installing composer
    cd /pub/bin
    # get composer signaure with retry
    while [ ! -s "$sig_file" ]; do
    php -r "copy('$sig_url', '$sig_file');"
    if [ ! -s "$sig_file" ]; then
        sleep 2
    fi
    done

    # get composer installer with retry
    while [ ! -s "$ins_file" ]; do
    php -r "copy('$ins_url', '$ins_file');"
    if [ ! -s "$ins_file" ]; then
        sleep 2
    fi
    done

    # check signature
    php -r "file_put_contents('$hash_file', hash_file('SHA384', '$ins_file'));"
    sig_data=<"$sig_file"
    hash_data=<"$hash_file"
    if [ "$sig_data" != "$hash_data" ]; then
        echo Invalid composer signature
        exit 2
    fi

    # run composer installer
    php $ins_file


    # cleanup
    rm $sig_file
    rm $hash_file
    rm $ins_file
    ln /pub/bin/composer.phar /pub/bin/composer
}

###
# sets up the tester user
#
setupTesterUser()
{
    echo Adding tester user
    addgroup -g 1001 tester
    adduser -u 1001 -G tester -h /pub -H -D -s /bin/ash tester
    echo "tester ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers
}

installComposer
setupTesterUser
