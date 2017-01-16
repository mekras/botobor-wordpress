.PHONY: clean

botobor-wordpress.zip: botobor/languages/botobor-ru_RU.mo
	zip -r botobor-wordpress botobor

botobor/languages/botobor-ru_RU.mo:
	msgfmt -o botobor/languages/botobor-ru_RU.mo botobor/languages/botobor-ru_RU.po

clean :
	-rm botobor-wordpress.zip
	-rm botobor/languages/*.mo
