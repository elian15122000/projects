#include <stdio.h>
#include <math.h>
#include <stdlib.h>

int main()
{
	double a, b, c, d, e, f; /* Variablen für x1, x2, x3 Wert von Vektor 1 und Vektor 2 (a = x1 Vektor 1, d = x1 Vektor zwei usw) */
	double ergebnis1;
	double ergebnis2;
	double ergebnis3;
	double wurzel1;  /* Variablen für Zwischenergebnisse */
	double wurzel2;
	double val;
	double ergebnis4; /* Variable für Endergebnis (Winkel) */
	double k;

	printf("Vektorrechnung\n");  /* Eingabe für die Werte der Vekoren 1 und 2 */
	printf("\nGeben Sie den x1 Wert des 1. Vektors ein: ");
	scanf("%lf", &a);
	printf("Geben Sie den x2 Wert des 1. Vektors ein: ");
	scanf("%lf", &b);
	printf("Geben Sie den x3 Wert des 1. Vektors ein: ");
	scanf("%lf", &c);
	printf("\nVektor 1 ist (%1.0f | %1.0f | %1.0f)\n", a, b, c);

	printf("\nGeben Sie den x1 Wert des 2. Vektors ein: ");
	scanf("%lf", &d);
	printf("Geben Sie den x2 Wert des 2. Vektors ein: ");
	scanf("%lf", &e);
	printf("Geben Sie den x3 Wert des 2. Vektors ein: ");
	scanf("%lf", &f);
	printf("\nVektor 2 ist (%1.0f | %1.0f | %1.0f)\n", d, e, f);

	ergebnis1 = (a * d) + (b * e) + (c * f);  /* Berechnung von den Zwischenergebnissen, Länge und des Endergebnisses der Formel für den Winkel zwischen zwei Vektoren */ 
	ergebnis2 = (a * a) + (b * b) + (c * c);
	ergebnis3 = (d * d) + (e * e) + (f * f);
	wurzel1 = sqrt(ergebnis2);
	wurzel2 = sqrt(ergebnis3);
	val = 180 / 3.14159;
	ergebnis4 = acos(ergebnis1 / (wurzel1 * wurzel2)) * val;
	k = a / d;
	
	if(abs(k) < 1) {  
	    k = d / a;    /* Zwischenrechnung für vielfache Vektoren */
	}
	
    printf("\nDer Vektor 1 hat die Laenge %1.1f", wurzel1); /* Ausgabe Laenge */
	printf("\nDer Vektor 2 hat die Laenge %1.1f", wurzel2);
	
	if (b * k == e && c * k == f) {  /* Prüfen, ob Vektoren in entgegengestezte oder gleiche Richtung zeigen, wenn sie Vielfache sind */
	    if(k > 0 ) 
	        printf("\nDer Winkel zwischen beiden Vektoren ist %f Grad\n" , 0.0);
	    else 
	        printf("\nDer Winkel zwischen beiden Vektoren ist %f Grad\n" , 180.0);
	} else
	{  /* Ansonsten Ausgabe von den Ergebnissen (Laenge von Vektoren und Winkel zwischen Vektoren) */
		printf("\nDer Winkel zwischen beiden Vektoren ist %f Grad\n", ergebnis4);
	}

	if (wurzel1 == 0 || wurzel2 == 0) {  /* Prüfen ob alle Eingaben = 0 oder eine Eingabe falsch war */
	    printf("\nNicht möglich\n");
	} 

	return 0;
}