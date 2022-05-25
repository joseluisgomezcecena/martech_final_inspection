###################
Final Inspection App
###################


Final inspection application to track orders when they enter quality inspection, this app measures times between quality
operations from receiving order to closing the inspection.

This app also lets production and quality users generate reports.


*******************
Release Information
*******************

This repo contains in-development code for future releases.

*******************
Server Requirements
*******************

PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

***************
Database
***************


Status column:
0 Rechazado,
1 Aceptado,
2 En espera por cambio de prioridad


Progress Column
0 alta, (fue ingresada por produccion y no ha sido asignada a ningun inspector)
1 asignada
2 liberada
3 cerrada
