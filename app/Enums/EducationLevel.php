<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EducationLevel: string implements HasLabel
{
    case None = 'none';
    case PrimarySchool = 'primary_school';
    case JuniorHighSchool = 'junior_high_school';
    case SeniorHighSchool = 'senior_high_school';
    case Diploma = 'diploma';
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Doctorate = 'doctorate';

    public function getLabel(): string
    {
        return match ($this) {
            self::None => 'No Schooling',
            self::PrimarySchool => 'Primary School',
            self::JuniorHighSchool => 'Junior High School',
            self::SeniorHighSchool => 'Senior High School',
            self::Diploma => 'Diploma I-III',
            self::Bachelor => 'D4 / S1',
            self::Master => 'S2',
            self::Doctorate => 'S3',
        };
    }
}
