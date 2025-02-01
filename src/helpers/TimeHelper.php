<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 namespace DevsStudio\Phptime\Helpers;

/**
 * Description of USTime
 *
 * @author JEANPAPER
 */
class TimeHelper
{

    const SYSTEM_DATE_FORMAT = 'Y-m-d';
    const SYSTEM_DATETIME_FORMAT = 'Y-m-d H:i:s';
    const PHP_DATE_FORMAT = 'd/m/Y';
    const PHP_TIME_FORMAT = 'h:i A';
    const PHP_DATETIME_FORMAT = 'd/m/Y h:i:s A';

    //STATIC
    private static $_digits = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Obtiene el tiempo actual en el formato determinado
     * @param string $p_s_format Formato
     * @return string Valor del tiempo
     */
    public static function now($p_s_format = self::SYSTEM_DATETIME_FORMAT)
    {
        return date($p_s_format);
    }

    /**
     * Obtiene una fecha y hora
     * @param integer $p_i_unix Valor UNIX a convertir
     * @param string $p_s_format Formato de la fecha reusltante
     * @return string Fecha en el formato indicado
     */
    public static function getFromUnix($p_i_unix, $p_s_format = self::SYSTEM_DATETIME_FORMAT)
    {
        return date($p_s_format, $p_i_unix);
    }

    /**
     * Obtiene un código único
     * @param boolean $p_b_upper Todo en mayúscula o NO
     * @return string Código único
     */
    public static function getDateCode($p_b_upper = false)
    {

        $s_date = self::changeBase10toN(date("YmdHis"/* , strtotime('-5 hours') */), 36);
        $s_datemicro = self::changeBase10toN(substr((string) microtime(), 2, 7), 36);

        //        return date("YmdHis"/* , strtotime('-5 hours') */) . substr((string) microtime(), 2, 7);
        if ($p_b_upper) {
            return strtoupper($s_date . $s_datemicro);
        } else {
            return $s_date . $s_datemicro;
        }
    }

    /**
     * Convierte un número decimal a base N
     * @param string $p_s_number Número
     * @param integer $p_i_base Base
     * @return string Número en base N
     * @throws Exception
     */
    public static function changeBase10toN($p_s_number, $p_i_base = 62)
    {

        if ($p_i_base > 62) {
            throw new \Exception('The limit is 62 basis');
        } else {
            $i_digits = self::$_digits;

            $s_new = "";

            while (bccomp($p_s_number, $p_i_base) != -1) {
                $s_new = $i_digits[(int) fmod($p_s_number, $p_i_base)] . $s_new;
                $p_s_number = bcdiv($p_s_number, $p_i_base);
            }

            $s_new = $i_digits[$p_s_number] . $s_new;

            return $s_new;
        }
    }

    /**
     * Obtiene una fecha númerica en excel a partir de una fecha númerica (UNIX)
     * @param integer $p_s_excel_date Fecha numérica de EXCEL
     * @return int Fecha en formato UNIX
     */
    public static function getUnixFromExcel($p_s_excel_date)
    {
        return ($p_s_excel_date - 25569) * 86400;
    }

    /**
     * Obtiene una fecha númerica en excel a partir de una fecha númerica (UNIX)
     * @param integer $p_s_unix_date Fecha numérica de UNIX
     * @return int Fecha en formato EXCEL
     */
    public static function getExcelFromUnix($p_s_unix_date)
    {
        return 25569 + ($p_s_unix_date / 86400);
    }

    /**
     * Obtiene el día de una fecha
     * @param string $p_s_date Fecha
     * @param string $p_s_format Format
     * @return string Valor del día
     */
    public static function getDayFromDate($p_s_date, $p_s_format = self::SYSTEM_DATE_FORMAT)
    {
        $o_datetime = \DateTime::createFromFormat($p_s_format, $p_s_date);
        return $o_datetime->format("d");
    }

    /**
     * Pasa un valor de fecha de un formato a otro
     * @param string $p_s_value Valor en formato de origen
     * @param string $p_s_from_format Formato de origen
     * @param string $p_s_to_format Formato de destino
     * @return string Valor en formato de destino
     * @throws \Exception
     */
    public static function format($p_s_value, $p_s_from_format, $p_s_to_format)
    {

        $o_datetime = \DateTime::createFromFormat($p_s_from_format, $p_s_value);

        if ($o_datetime == false) {
            throw new \Exception('Fecha inválida: ' . $p_s_value);
        }

        return $o_datetime->format($p_s_to_format);
    }
}
