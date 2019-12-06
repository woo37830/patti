# Makefile for LaTeX documentation
#
# See README.
#
# $Id: Makefile,v 1.2 2014/03/27 17:20:33 woo Exp $

NAME = Technical Manual
INPUTS = technical.tex
MAKEFILEDIR = $(HOME)/lib
PDFLATEX = pdflatex
TEX = $(PDFLATEX) -shell-escape -interaction=nonstopmode -file-line-error
BIBTEX = bibtex
PRE = $(TEX) -ini -job-name="preamble" &pdflatex preamble.tex\dump"
STYLE = 
BIB =

RM = rm -f

OTHER = README
GARBAGE = *.aux *.log *.bbl *.blg *.toc *.aux *.lof *.lot *.dvi
DEPEND = $(NAME).tex $(NAME).bib
FORCE = .silly_file_to_force_rebuild

TARFILES = $(NAME).tex $(INPUTS) $(STYLE) $(BIB) $(OTHER) Makefile
TARFILEDIR = $(HOME)/lib/tarfiles


### LaTeX Documentation

pdf: check_tools $(NAME).tex $(NAME).bbl $(NAME).blg
	@echo --- Pass 1 with LateX on $(NAME) to create aux files----
	@make $(NAME).pdf
	@make bib
	@echo --- Pass 2 with LateX on $(NAME) to resolve references ---
	@touch Makefile $(DEPEND)
	@make $(NAME).pdf
	@echo --- Pass 3 with LateX on $(NAME) for final  resolve references ---
	@touch Makefile $(DEPEND) $(NAME).aux
	@make $(NAME).pdf
	@echo  --- LaTeX phase finished ---- Use make view to print ---

$(NAME).pdf: $(FORCE) $(NAME).tex $(INPUTS) $(STYLE) $(BIB)
	$(TEX) $(NAME).tex


check_tools: TOOLS = $(BIBTEX) $(PDFLATEX)
check_tools:
	@echo "Checking build tools..."
	$(foreach tool,$(TOOLS),$(if $(shell which $(tool)),,$(error You seem to be misssing $(tool)!)))

do_force: 
	@echo "Forcing re-build"
	@touch Makefile $(DEPEND) $(NAME).bib
	@date > $(FORCE)

$(FORCE): check_tools
	@if [ ! -e $(FORCE) ]; then echo "" > $(FORCE); fi
     
force: do_force pdf
	@echo "Rebuid complete"
    
view: $(NAME).pdf
	open $(NAME).pdf
	
preamble.fmt : preamble.tex
	$(PRE) preamble.tex

aux : $(NAME).tex
	$(TEX) $(NAME).tex
 
 
bib: $(NAME).bbl
	@echo --- Fixing up the quotes and commas on article titles ---
	@mv $(NAME).bbl tmp.bbl
	@sed "s/,'',/,''/g" < tmp.bbl > $(NAME).bbl
	@/bin/rm tmp.bbl
	@echo --- Fixing up the ", (" in the articles ---
	@mv $(NAME).bbl tmp.bbl
	@sed "s/, (/ (/g" < tmp.bbl > $(NAME).bbl
	@/bin/rm tmp.bbl
	@echo --- Fixing up the " pp xxx in, " in the proceedings ---
	@mv $(NAME).bbl tmp.bbl
	@sed "s/in , /in /g" < tmp.bbl > $(NAME).bbl
	@/bin/rm tmp.bbl
		
$(NAME).bbl: $(NAME).dvi $(INPUTS) $(STYLE) $(BIB)
	@echo --- Beginning bibtex on $(NAME) ---
	-bibtex $(NAME)
	
prt: $(NAME).dvi
	@dvips $(NAME).dvi

preview:
	@TeXview $(NAME).dvi
	
# remove all derived files
clean:
	@rm -f $(GARBAGE)
	
spotless:
	@rm -f $(GARBAGE) $(NAME).dvi *~ 

list:: $(NAME).list

tar:: $(NAME).tar

tarlist:: $(NAME).tarlist

untar:: $(NAME).untar

xtract:: $(NAME).xtract

tardir:: $(NAME).tardir

tardiff:: $(NAME).tardiff
	
DATE:
	@if [ -n "${$@}" ]; then exit 0; \
	else echo Must define $@; exit 1; fi
	
DATE1:
	@if [ -n "${$@}" ]; then exit 0; \
	else echo Must define $@; exit 1; fi
	
DATE2:
	@if [ -n "${$@}" ]; then exit 0; \
	else echo Must define $@; exit 1; fi

$(NAME).tar:: DATE
	tar cf - $(TARFILES) | \
	(cd $(TARFILEDIR) ; compress > $(NAME).$(DATE).tar.Z )
	
$(NAME).untar:: DATE
	(cd $(TARFILEDIR) ; zcat $(NAME).$(DATE).tar.Z ) | \
	tar -xf - 
	
$(NAME).tarlist:: DATE
	(cd $(TARFILEDIR) ; zcat $(NAME).$(DATE).tar.Z ) | \
	tar -tf -
	
$(NAME).xtract:: DATE
	(cd $(TARFILEDIR) ; zcat $(NAME).$(DATE).tar.Z ) | \
	tar -xf - $(LIST)
	
$(NAME).tardir:
	(cd $(TARFILEDIR) ; /bin/ls $(NAME).*.tar.Z )
	
$(NAME).tardiff:: DATE1 DATE2
	/bin/rm -rf /tmp/tar.$(DATE1); \
	/bin/rm -rf /tmp/tar.$(DATE2); \
	/bin/rm  /tmp/tarlist ; \
	mkdir /tmp/tar.$(DATE1); \
	mkdir /tmp/tar.$(DATE2); \
	(cd $(TARFILEDIR); zcat $(NAME).$(DATE1).tar.Z ) | \
	(cd /tmp/tar.$(DATE1); tar -xf - ) ; \
	(cd $(TARFILEDIR); zcat $(NAME).$(DATE2).tar.Z ) | \
	(cd /tmp/tar.$(DATE2); tar -xf - ) ; \
	(cd /tmp/tar.$(DATE1); ls $(TARFILES) ) > /tmp/tarlist ; \
	(cd /tmp/tar.$(DATE2); ls $(TARFILES) ) >> /tmp/tarlist ; \
	for i in `cat /tmp/tarlist | sort -u` ; do	\
	diff /tmp/tar.$(DATE1)/$i /tmp/tar.$(DATE2)/$i ; \
	done 
	
$(NAME).list:
	enscript -fCourier8 $(TARFILES)
	
.SUFFIXES: .tex .plt

.plt.tex:
	gnuplot $<

help::
	@echo '  dvi ( make the dvi file  - DEFAULT )'
	@echo '  clean ( remove all but backups and .dvi file)'
	@echo '  spotless ( remove all but sources )'
	@echo '  bib ( make the .bbl file  from the .aux files'
	@echo '  list ( print listings of files)'
	@echo '  prt ( print the dvi file to lpr )'
	@echo '  preview ( preview the dvi file)'
	@echo '  tar [DATE=date]'
	@echo '  untar [DATE=date]'
	@echo '  tardir'
	@echo '  tarlist [DATE=date]'
	@echo '  xtract [DATE=date] [LIST=file list]'
	@echo '  tardiff [DATE1=date] [DATE2=date]'
