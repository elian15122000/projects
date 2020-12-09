#include <ctype.h>
#include <stdio.h>
#include <math.h>

int main(){
    
    double speed;  //variablen für die eingaben und die späteren berechnungen
    double m;
    double winkel;
    double w;
    double d;
    double Vx;
    double Vy;
    double h;
    double t;
    double G;
    
    
    printf("Eine Kugel mit dem Durchmesser d und der Masse m wird vom Boden in den Himmel geschossen.\n"); //erklärung des geschehens 
    printf("Reichweite W, Gipfelhohe H und Flugdauer T werden\n");
    printf("-nach der klassichen Methode (Ebene Erde, keine Athmosphaere)\n");
    printf("-durch Simulation (kugelfoermige Erde, Luftwiderstand) berechnet\n");
    Loop1: printf("Geschwindigkeit (0<v0<=5000)[m/s]:");  //eingabe der variablen für die berechnung der kurve und dessen werte + beschränkungen der eingabe durch loops
    scanf("%lf", &speed);
    if(speed < 0 || speed > 5000) {
        getchar();
        printf("Bitte Bedinungen beachten und erneut eingeben!\n");
        goto Loop1;
    }
    Loop2: printf("alpha (0<alpha<=90) [grad] : ");
    scanf("%lf", &winkel);
    if(winkel < 0 || winkel > 90) {
        getchar();
        printf("Bitte Bedinungen beachten und erneut eingeben!\n");
        goto Loop2;
    }
    Loop3: printf("Durchmesser (0<=d<=20) [cm] : ");
    scanf("%lf",&d);
    if(d < 0 || d > 20) {
        getchar();
        printf("Bitte Bedinungen beachten und erneut eingeben!\n");
        goto Loop3;
    }
    Loop4: printf("Masse (0<m<=100) [kg] : ");
    scanf ("%lf",&m);
    if(m < 0 || m > 100) {
        getchar();
        printf("Bitte Bedinungen beachten und erneut eingeben!\n");
        goto Loop4;
    }

    G = 9.81;  //berechnung der reichweite, flugdauer und gipfelhöhe
    Vy = speed * sin(winkel);
    Vx = speed * cos(winkel);
    w = 2*Vx*Vy / G;
    h = Vy * Vy / 2*G;
    t = 2*Vy/G;                       
    printf("Klassicher Fall:\n"); //augabe der ergebnisse
    printf ("Reichweite: %f\n",w);
    printf("Flugdauer: %f\n",t);
    printf("Gipfelhoehe: %f\n",h);
              

return 0;

} 

          
               
        
      