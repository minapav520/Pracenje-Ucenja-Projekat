# 🎓 Student Planner - Sistem za praćenje rada učenika

Student Planner je veb aplikacija razvijena u okviru ispitnog projekta, namenjena profesorima i razrednim starešinama za efikasno **praćenje napretka i rada učenika kod kuće**. 

Sistem kombinuje moć PHP objektnog programiranja (OOP) i MySQL baze podataka sa modernim, responzivnim i estetski privlačnim Bootstrap interfejsom.

## 👥 Primena aplikacije

Aplikacija je projektovana kao **centralni administratorski panel za profesora**. Glavni cilj sistema je da nastavniku pruži jasan, vizuelan i statistički pregled nad aktivnostima učenika van škole:

* **Unos i evidencija:** Profesor za svakog učenika unosi predmet, konkretan zadatak ili temu koju je radio kod kuće, vreme provedeno u učenju (u minutima), kao i ocenu koju je učenik dobio ili zaslužio.
* **Globalna analitika:** Gornje statičke kartice dinamički računaju i prikazuju podatke na nivou celog razreda (ukupan broj aktivnih učenika, broj predmeta i ukupne sate učenja).
* **Napredno filtriranje:** Kako bi profesor mogao da obavi individualne konsultacije ili analizira rad samo jednog određenog đaka, ugrađen je **filter**. Izborom učenika iz padajućeg menija, cela tabela se izoluje, a gornje kartice automatski preračunavaju sate učenja i statistiku **isključivo za tog učenika**.
