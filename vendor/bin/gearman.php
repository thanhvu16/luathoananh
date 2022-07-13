#!/usr/bin/env sh
SRC_DIR="`pwd`"
cd "`dirname "$0"`"
cd "../sinergi/gearman/bin"
BIN_TARGET="`pwd`/gearman.php"
cd "$SRC_DIR"
"$BIN_TARGET" "$@"
