#!/bin/bash
texBin='/Library/TeX/texbin'
if [[ $# -eq 0 ]]; then
    echo 'Useage: create_document.sh documentName'
    exit 0
fi
/bin/echo --- First pass to create aux files
$texBin/pdflatex $1
/bin/echo --- Fixing up the quotes and commas on article titles ---
	/bin/mv $1.bbl tmp.bbl
	/usr/bin/sed "s/,'',/,''/g" < tmp.bbl > $1.bbl
	/bin/rm -f tmp.bbl
	/bin/echo --- Fixing up the ", (" in the articles ---
	/bin/mv $1.bbl tmp.bbl
	/usr/bin/sed "s/, (/ (/g" < tmp.bbl > $1.bbl
	/bin/rm -f tmp.bbl
	/bin/echo --- Fixing up the " pp xxx in, " in the proceedings ---
	/bin/mv $1.bbl tmp.bbl
	/usr/bin/sed "s/in , /in /g" < tmp.bbl > $1.bbl
	/bin/rm -f tmp.bbl
$texBin/bibtex $1
/bin/echo --- Second pass to update aux for bib changes
$texBin/pdflatex $1
/bin/echo --- Third and final pass to output finished document
$texBin/pdflatex $1
/bin/rm -f *.aux *.idx *.lof *.lot *.toc *.bib *.blg *.bbl
