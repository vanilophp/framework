# Build Tools (Internal)

## Release Checklist

- Modify Changelog and manifest entries
- Commit as `Release v5.x.y` (DO NOT TAG IT!)
- Push to master
- Run `build-tools/split.sh`
- When not all the modules will be released, then change line 52 in `release.sh` temporarily 
  so that it only contain the changed modules that will be released. Otherwise, unchanged
  modules will get the tag as well, i.e. the previously released commit will have the
  previous tag and the new tag as well
- Run `build-tools/release.sh 4.x.y`
