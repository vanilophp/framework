#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="master"

function split()
{
    if [ -n "$3" ]; then # Optional starting commit
        SHA1=$(splitsh-lite -scratch --prefix=$1 --commit $3)
    else
        SHA1=$(splitsh-lite -scratch --prefix=$1)
    fi

    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    REPO="git@github.com:vanilophp/$1.git"
    git remote add $1 $REPO || true
}

#git pull origin $CURRENT_BRANCH

remote cart
remote category
remote channel
remote checkout
remote contracts
remote order
remote payment
remote product
remote properties
remote support

#split 'src/Adjustments' adjustments
split 'src/Cart' cart
split 'src/Category' category
split 'src/Channel' channel
split 'src/Checkout' checkout
split 'src/Contracts' contracts 8683e47dd2dbd15ac2ceac4dcfae405c4b271aff
split 'src/Order' order
split 'src/Payment' payment
split 'src/Product' product
split 'src/Properties' properties
#split 'src/Shipping' shipping
split 'src/Support' support 8683e47dd2dbd15ac2ceac4dcfae405c4b271aff
