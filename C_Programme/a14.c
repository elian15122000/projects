#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

int main()
{
	double k, z, n, r, i, q;       /* Deklarierung der benoetigten Variablen */
	char eingabe;

Loop:
	printf("Welche Groesse wollen Sie berechnen?\n(K)apital, (Z)insen, (P)erdiodenzahl, (R)ate? %c", eingabe);
	scanf("%c", &eingabe);   /* Einlesen der Eingabe  */

	while(getchar() != '\n') {  /* Das erste Zeichen von Eingabe einlesen */
		
	}

    switch(eingabe) {  /* Eingabe pruefen ob K, Z, P oder R berechnet werden soll */
        case 'K':
        case 'k':
            rate1:
		    printf("Rate   >0: ");    /* Eingabe Rate */
		    scanf("%lf", &r);
		
            if (r <= 0)   /* Pruefen, ob Eingabe der Rate groesser 0 ist (das Gleiche mit Zins, Kapital und Periode bei den verschiedenen cases) */
            {
                printf("Rate muss >0 sein!\n");
                goto rate1;
            }
            zins1:    /* Eingabe Zins */
                printf("Zins   >0: ");
                scanf("%lf", &z);
                if (z <= 0)
                {
                    printf("Zins muss >0 sein!\n");
                    goto zins1;
                } 
            periode1:   /* Eingabe Periode */
                printf("Periode   >0: ");
                scanf("%lf", &n);
                if (n <= 0)
                {
                    printf("Periode muss >0 sein!\n");
                    goto periode1;
                }

                z = 1 + z / 100;   /* Berechnung Kapital anhand Formel und Eingaben */
                k = r * z * ((pow(z, n) - 1) / (z - 1));
                printf("Kapital: %f\n", k);    /* Ausgabe Kapital */

            return 0;
            break;
        
        case 'Z':   /* Zinsrechnung */
        case 'z':
            kapital1:
            printf("Kapital   >0: ");
            scanf("%lf", &k);    /* Eingabe Kapital */
            if (k <= 0)
            {
                printf("Kapital muss >0 sein!\n");
                goto kapital1;
            }
            rate3:
                printf("Rate   >0: ");
                scanf("%lf", &r);    /* Eingabe Rate */
                if (r <= 0)
                {
                    printf("Rate muss >0 sein!\n");
                    goto rate3;
                }
            periode2:
                printf("Periode   >0: ");
                scanf("%lf", &n);  /* Eingabe Periode */
                if (n <= 0)
                {
                    printf("Periode muss >0 sein!\n");
                    goto periode2;
                }

                if (r * n >= k)     /* Berechnung ob sich Zins lohnt falls Eingaben keinen Sinn machen */
                {
                    printf("Wozu ein Fonds???\n");
                    return 0;
                }

                z = 1;			/* Berechnung Zins */
                i = z / 100;
                q = 1 + i;
                while (r * q * (pow(q, n) - 1) / i < k)
                {
                    z++;
                    i = z / 100;
                    q = 1 + i;
                    while (r * q * (pow(q, n) - 1) / i > k)
                    {
                        z = z - 0.1;
                        i = z / 100;
                        q = 1 + i;
                        while (r * q * (pow(q, n) - 1) / i < k)
                        {
                            z = z + 0.01;
                            i = z / 100;
                            q = 1 + i;
                            if (r * q * (pow(q, n) - 1) / i > k)
                            {
                                z = z - 0.01;
                                printf("Zins: %f\n", z);    /* Zins Ausgabe */
                                return 0;
                            }
                        }
                    }
                }

                while (r * q * (pow(q, n) - 1) / i > k)
                {
                    z = z - 0.1;
                    i = z / 100;
                    q = 1 + i;
                    while (r * q * (pow(q, n) - 1) / i < k)
                    {
                        z = z + 0.01;
                        i = z / 100;
                        q = 1 + i;
                        if (r * q * (pow(q, n) - 1) / i > k)
                        {
                            z = z - 0.01;
                            printf("Zins: %f\n", z);
                            return 0;
                        }
                    }
                }
        break;


        case 'P':      /* Periode Auswahl berechnen */
        case 'p':
            rate2:
            printf("Rate   >0: ");  
            scanf("%lf", &r);      /* Eingabe Rate */
            if (r <= 0)
            {
                printf("Rate muss >0 sein!\n");
                goto rate2;
            }
            kapital2:
                printf("Kapital   >0: ");
                scanf("%lf", &k);      /* Eingabe Kapital */
                if (k <= 0)
                {
                    printf("Kapital muss >0 sein!\n");
                    goto kapital2;
                }
            zins2:
                printf("Zins   >0: ");
                scanf("%lf", &z);      /* Eingabe Zins */
                if (z <= 0)
                {
                    printf("Zins muss >0 sein!\n");
                    goto zins2;
                }
                z = 1 + z / 100;       /* Periode Berechnung */
                n = ceil(log(k * (z - 1) / r + z) / log(z) - 1);
                printf("Periode: %f\n", n);      /* Ausgabe Periode */

                return 0;
        break;


        case 'R':     /* Berechnung Rate */
        case 'r':
            periode3:
            printf("Periode   >0: ");
            scanf("%lf", &n);       /* Periode Eingabe */
            if (n <= 0)
            {
                printf("Periode muss >0 sein!\n");
                goto periode3;
            }
            kapital3:
                printf("Kapital   >0: ");
                scanf("%lf", &k);     /* Kapital Eingabe */
                if (k <= 0)
                {
                    printf("Kapital muss >0 sein!\n");
                    goto kapital3;
                }
            zins3:
                printf("Zins   >0: ");
                scanf("%lf", &z);    /* Zins Eingabe */
                if (z <= 0)
                {
                    printf("Zins muss >0 sein!\n");
                    goto zins3;
                }

                z = 1 + z / 100;     /* Rate Berechnung */
                r = k * ((z - 1) / (z * (pow(z, n) - 1)));
                printf("Rate: %f\n", r);      /* Rate Ausgabe */

                return 0;
        break;

        default:
        	printf("Sie haben keine der zur Auswahl stehenden Angebote eingetippt. Bitte nochmal versuchen!\n");   /* Falls K,k,Z,z,P,p,R,r eingetippt wurde bzw erstes Zeichen war zu Loop gehen wieder und neue Eingabe starten */
			goto Loop;

    }


    return 0;
} 