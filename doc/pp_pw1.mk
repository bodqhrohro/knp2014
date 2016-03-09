.PHONY: all
all: uc doc
uc:
	cat pp_pw1_uc.pu | plantuml -tlatex -p > pp_pw1_uc.tex
doc:
	xelatex pp_pw1.tex
