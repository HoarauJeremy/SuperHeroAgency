<?php

namespace App\Entity;

enum Statut: string{
    case PENDING = "EN ATTENTE";
    case IN_PROGRESS = "EN COURS";
    case COMPLETED = "TERMINER";
    case FAILURE = "ECHOUÉE";
}

