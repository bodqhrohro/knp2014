.PHONY: all
all: uc doc
uc:
	plantuml pp_pw1_uc.pu
	plantuml pp_pw3_conc.pu
	plantuml pp_pw3_clas.pu
doc:
	xelatex dpl4.tex
imglist:
	grep -R imglabel *.tex|grep -oP '(?<=imglabel{).*(?=})'|paste -sd,|sed 's/,/, /g' > dpl4_imglist
sectionlist:
	grep -R '\\section{' *_inc.tex|grep -oP '(?<=\\section{).*(?=})'|paste -sd,|sed 's/,/, /g' > dpl4_sectionlist
