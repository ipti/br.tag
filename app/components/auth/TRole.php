<?php

enum TRole: string {
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case READER = 'reader';
    case COORDINATOR = 'coordinator';
    case INSTRUCTOR = 'instructor';
    case GUARDIAN = 'guardian';
    case NUTRITIONIST = 'nutritionist';
}
