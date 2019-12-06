#!/bin/bash
# Script to create the most recent pdf docs and upload them to the
# server.
pdflatex technical.tex > /dev/null 2>&1
pdflatex technical.tex > /dev/null 2>&1
pdflatex technical.tex > /dev/null 2>&1
echo technical.pdf completed
#
#pdflatex user_manual.tex > /dev/null 2>&1
#pdflatex user_manual.tex > /dev/null 2>&1
#pdflatex user_manual.tex > /dev/null 2>&1
#echo user_manual.pdf completed
#
#pdflatex admin_manual.tex > /dev/null 2>&1
#pdflatex admin_manual.tex > /dev/null 2>&1
#pdflatex admin_manual.tex > /dev/null 2>&1
#echo user_manual.pdf completed
#
#pdflatex ArchiverInstallationManual.tex > /dev/null 2>&1
#pdflatex ArchiverInstallationManual.tex > /dev/null 2>&1
#pdflatex ArchiverInstallationManual.tex > /dev/null 2>&1
#echo ArchiverInstallationManual.pdf completed
#
#pdflatex DesignNotes.tex > /dev/null 2>&1
#pdflatex DesignNotes.tex > /dev/null 2>&1
#pdflatex DesignNotes.tex > /dev/null 2>&1
#echo DesignNotes.pdf completed
#
#pdflatex DatabaseTools.tex > /dev/null 2>&1
#pdflatex DatabaseTools.tex > /dev/null 2>&1
#pdflatex DatabaseTools.tex > /dev/null 2>&1
#echo DatabaseTools.pdf completed
#
#pdflatex TestPlan.tex > /dev/null 2>&1
#pdflatex TestPlan.tex > /dev/null 2>&1
#pdflatex TestPlan.tex > /dev/null 2>&1
#echo TestPlan.pdf completed
#
rm -rf *.aux
rm -rf *.idx
rm -rf *.lof
rm -rf *.lot
rm -rf *.toc
rm -rf *.log
rm -rf *.dvi
echo Done with pdflatex
#~/bin/rsync_doc.sh
#echo Documentation synced to barsoom
