#!/usr/bin/env bash

## On Major Release
# - Replace docs/master/* to docs/new.x/*

set -e

echo "Make sure to run split.sh before doing this"
#exit 0
# uncomment the line above once it's done

# Make sure the release tag is provided.
if (( "$#" != 1 ))
then
    echo "Tag has to be provided."
    exit 1
fi

RELEASE_BRANCH="4.x"
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
VERSION=$1

# Make sure current branch and release branch match.
if [[ "$RELEASE_BRANCH" != "$CURRENT_BRANCH" ]]
then
    echo "Release branch ($RELEASE_BRANCH) does not match the current active branch ($CURRENT_BRANCH)."
    exit 1
fi

# Make sure the working directory is clear.
if [[ ! -z "$(git status --porcelain)" ]]
then
    echo "Your working directory is dirty. Did you forget to commit your changes?"
    exit 1
fi

# Make sure latest changes are fetched first.
git fetch origin

# Make sure that release branch is in sync with origin.
if [[ $(git rev-parse HEAD) != $(git rev-parse origin/$RELEASE_BRANCH) ]]
then
    echo "Your branch is out of date with its upstream. Did you forget to pull or push any changes before releasing?"
    exit 1
fi

# Tag Framework
git tag $VERSION
git push origin --tags

# Tag Components
for REMOTE in adjustments cart category channel checkout contracts links master-product order payment product promotion properties shipment support taxes
do
    echo ""
    echo ""
    echo "Releasing $REMOTE";

    TMP_DIR="$HOME/tmp/vanilo-split"
    REMOTE_URL="git@github.com:vanilophp/$REMOTE.git"

    rm -rf $TMP_DIR;
    mkdir -p $TMP_DIR;

    (
        cd $TMP_DIR;

        git clone $REMOTE_URL .
        git checkout "$RELEASE_BRANCH";

        git tag $VERSION
        git push origin --tags
    )
done
