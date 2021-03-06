<?php
namespace Bookly\Backend\Components\Dialogs\Appointment\Edit\Proxy;

use Bookly\Lib;

/**
 * Class RecurringAppointments
 * @package Bookly\Backend\Components\Dialogs\Appointment\Edit\Proxy
 *
 * @method static void createBackendPayment( Lib\Entities\Series $series, array $customer ) Create payment for series.
 * @method static void renderReschedule() Render reschedule in edit appointment dialog.
 * @method static void renderSchedule() Render schedule in edit appointment dialog.
 * @method static void renderSubForm() Add Recurring sub form in edit appointment dialog.
 */
abstract class RecurringAppointments extends Lib\Base\Proxy
{

}