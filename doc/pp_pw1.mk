.PHONY: all
all: uc doc
uc:
	plantuml pp_pw1_uc.pu
doc:
	xelatex pp_pw1.tex
