#include <stdio.h>
#include <stdlib.h>
#include <string.h>

char* linie(char *linie);

int main () { 
        double x, y;
        char *string = NULL;   /* Deklarierung von benötigten Variablen (und Initialisierung von "string" Pointer vom Typ char) */   
        char *pointer; 
        
        zahl1: 
        printf("Erste Zahl:");
        string = linie(string);          /*  Eingabe und Einlesen von vermeinntlichem ersten Doubel Wert */
        x = strtod(string, &pointer);
        
        if(strlen(pointer) != 0) {            /* Ueberprüfung auf Datentyp double*/
                fprintf(stderr, "Fehlerhafte Eingabe\n");
                goto zahl1;           /* Bei Eingabe von keinem Double Wert zurueck zur Eingabe von erster Zahl */
        } else if(strlen(string) == 0) {    /* Ueberpruefung auf leere Eingabe */
                fprintf(stderr, "Leere Eingabe\n");
                goto zahl1;      /* Bei leerer Eingabe zureck zur Eingabe zweiter Zahl */
        }
        
        zahl2: 
        printf("Zweite Zahl:");
        string = linie(string);                /*  Eingabe und Einlesen von vermeinntlichem zweiten Doubel Wert */
        y = strtod(string, &pointer);
        if(strlen(pointer) != 0) {             /* Ueberpruefung auf Datentyp double*/
                fprintf(stderr, "Fehlerhafte Eingabe\n");
                goto zahl2;           /* Bei Eingabe von keinem Double Wert zurueck zur Eingabe von zweiter Zahl */
        } else if(strlen(string) == 0) {  /* Ueberpruefung auf leere Eingabe bei Zahl 2*/
                fprintf(stderr, "Leere Eingabe\n");
                goto zahl2;  /* Bei leerer Eingabe zureck zur Eingabe zweiter Zahl */
        }


        printf("Ergebnis: %f\n", x + y);            /* Ergebnis ausgeben (Voraussetzung: Beide Zahlen sind Double Werte) */
        

        return(0);
}

char* linie(char *linie)
{  
        char *tmp;
        unsigned groesse;
        groesse = 0u;
        linie = NULL;
        tmp = linie;        /* Deklarierung von benötigten Variablen (und Initialisierung von unsigned groesse)  -- 'Linie' soll Zeile sein */

        do {
                tmp = (char *) realloc(linie, groesse++);        /* Speicher erweitern */
                if(tmp == NULL)               
                {
                        printf("Speicherverwaltung hat nicht funktioniert!\n");
                        free(linie);           /* Linie (Zeile) befreien */
                        return NULL;
                }
                linie = tmp;
                linie[groesse - 1] = getchar();        
        } while(linie[groesse - 1] != '\n');

        linie[groesse - 1] = '\0';
        return linie;      
}