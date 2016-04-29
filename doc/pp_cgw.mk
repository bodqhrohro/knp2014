.PHONY: all
all: uc doc
uc:
	plantuml pp_pw1_uc.pu
	plantuml pp_pw3_conc.pu
	plantuml pp_pw3_clas.pu
doc:
	xelatex pp_pw1.tex
