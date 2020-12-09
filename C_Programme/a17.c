#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

int main(int argc, char const *argv[])
{
    double m = 0;    /* Deklarierung und z.T. Initialisierung der benötigten Variablen */
    double s = 0;
    double d = 0;
    double std = 0;
    int j = 0;
    int z = 0;
    int f = 0;
    int b = 0;
    int i = 0;
    char wtr;
    double *temp;
    double *arr = malloc(sizeof(double));      /* Speichern von arr der später eingelesen wird */
    
    if (arr == NULL)   /* Fehlerausgabe und Exit, wenn arr == NULL ist */
	{
		printf("Fehler beim Speichern\n");
		exit(EXIT_FAILURE);
	}

    printf("Wieviele Zahlen sollen eingegeben werden? (<=0, wenn unbekannt...): ");
    scanf("%d", &b);     /* Eingabe von Anzahl der einzugebenden Zahlen */

    if (b <= 0) {    /* Einzelne Eingabe wenn b <= 0 ist */		
            loop:
           
            printf("Zahl %d: ", f + 1);
            scanf("%lf", &arr[f]);     /* Eingabe erster Zahl */
            f++;
            printf("Weiter? (j/...) ");
            scanf(" %c", &wtr);      /* Eingabe um j zu pruefen bzw ob es weitergeht */

            if (wtr == 'j') {    /* Wenn j eingegeben wird, dann neue Zahl bzw Eingabe */
                temp = (double*) realloc(arr, sizeof(double) * (f + 1));      /* Zwischenspeichern und preufen ob allokieren schief geht */
                if (temp == NULL){
			    printf("Fehler beim Speichern\n");
			    exit(EXIT_FAILURE);
		        } 
                arr = temp;
                goto loop;
            } else {
                for (j = 0; j < f; j++) {      /* Wenn j nicht eingegeben wird sondern etwas anderes, dann wird Standardabweichung und Durchschnitt berechnet anhand der vorigen Eingaben */
                    d += arr[j];
                }                       
                m = d / f;     /* Mittelwert */

                for (z = 0; z < f; z++) {      /* Berechnung Standardabweichung */
                    s += pow(arr[z] - m, 2);
                }
                std = sqrt(s / f);   /* Standardabweichung */

                printf("Durchschnitt: %E\n", m);     /* Ausgabe Durchschnitt und Standardabweichung */
                printf("Standardabweichung: %E\n", std); 

                free(arr);
                
                return 0;
            }
        
    } else {
        temp = (double*) realloc(arr, sizeof(double) * (b));     /* Zwischenspeichern und preufen ob allokieren schief geht */

        if (temp == NULL) {
			printf("Fehler beim Speichern\n");
			exit(EXIT_FAILURE);
		} else {
           arr = temp;
        }

    }

    for (i = 0; i < b; i++) {     /* Wenn Eingabe groesser 0 ist, dann wird dementsprechend oft durch diese Schleife Zahlen eingegeben */
        printf("Zahl %d: ", i + 1);
        scanf("%lf", &arr[i]);     /* Eingabe */
    }

    for (j = 0; j < b; j++) {      /* Berechnung Mittelwert */
        d += arr[j];
    }
    m = d / b;    /* Mittelwert */

    for (z = 0; z < b; z++) {      /* Berechnung Standardabweichung */
        s += pow(arr[z] - m, 2);
    }
    std = sqrt(s / b);      /* Standardabweichung */

    printf("Durchschnitt: %E\n", m);      /* Ausgabe Ergebnisse */
    printf("Standardabweichung: %E\n", std);     

    free(arr);      /* arr befreien */

    return 0;
}  