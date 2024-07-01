<?php

namespace App;

class Constants
{
    const APPOINTMENT_STATUSES = ['PENDING', 'APPROVED', 'DONE', 'FAILED'];
    const APPOINTMENT_STATUSES_STRING = 'PENDING,APPROVED,DONE,FAILED';
    const APPOINTMENT_PENDING = 'PENDING';
    const APPOINTMENT_APPROVED = 'APPROVED';
    const APPOINTMENT_DONE = 'DONE';
    const APPOINTMENT_FAILED = 'FAILED';

    const APPOINTMENT_APPROVED_STUDENT = 'Balance Deduction from approved Appointment';
    const APPOINTMENT_DONE_MENTOR = 'Balance Increase from completed Appointment';
    const APPOINTMENT_FAILED_STUDENT = 'Balance Rollback from failed Appointment';
}
