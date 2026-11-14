<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Boleta de Notas Secundaria</title> 

    <style>
        html {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 10px;
        }

        /* Estilos para la imagen de la insignia */
        .insignia {
            width: 85px;

        }

        /* Estilos al td contenedor de la insignia */
        .td-insignia {
            vertical-align: top;
            text-align: center;
            padding-top: 10px;
        }

        /* Columna celesta en todo lo alto de la hoja */
        .tabla-margen {
            height: 99%;
            width: 13%;
            background-color: #9CDBF2;
        }

        .aprobado {
            color: #000000;
        }

        .jalado {
            color: #FF0000;
        }
    </style>
</head>

<body>

    <table class="tabla-margen">
        <tr>
            <td class="td-insignia">
                <img src="/home3/rdescartes/public_html/intranet/public/imagenes/insignia-pdf.png" class="insignia">
            </td>
        </tr>
    </table>

    <table style="width: 85%;float: right;position: fixed; text-align: center;">
        <tr>
            <td>
                <label style="font-size: 15px; color: #312380;">INSTITUCIÓN EDUCATIVA PRIVADA</label> <br>
                <label style="font-weight: bold;font-size: 25px; color: #312380;">RENÉ DESCARTES</label> <br>
                <label style="font-size: 10px; color: #9B2A2A;">PRIMARIA - SECUNDARIA</label>
            </td>
        </tr>
    </table>

    <hr style="width: 82%; margin-top: 85px; border: 1px solid #3D386D; position: fixed; float: right;">

    <table style="width: 85%; position: fixed; float: right;margin-top: 100px;">
        <tr style="text-align: center; font-size: 18px; color: #312380; font-weight: bold;">
            <td colspan="2">REPORTE BIMESTRAL DE NOTAS {$anio_escolar}</td>
        </tr>
        <tr>
            <td style="color: #3D386D;font-weight: bold; width: 150px;">Apellidos y Nombres:</td>
            <td style="color: #3D386D;">{$alumno}</td>
        </tr>
        <tr>
            <td style="color: #3D386D;font-weight: bold;">Grado:</td>
            <td style="color: #3D386D;">{$grado}</td>
        </tr>
        <tr>
            <td style="color: #3D386D;font-weight: bold;">Tutor / Tutora:</td>
            <td style="color: #3D386D;"></td>
        </tr>
    </table>

    <table border="1" style="width: 85%; position: fixed; margin-top: 220px; padding-left: 50px; margin-left: auto; margin-right: auto;" cellspacing="0">
        <tr style="background-color: #d3d3d3;">
            <td style="width: 250px; text-align: center; font-weight: bold;">CURSOS</td>
            <td style="text-align: center; font-weight: bold;">1er <br> Bim.</td>
            <td style="text-align: center; font-weight: bold;">2do <br> Bim.</td>
            <td style="text-align: center; font-weight: bold;">3ro <br> Bim.</td>
            <td style="text-align: center; font-weight: bold;">4to <br> Bim.</td>
            <td style="text-align: center; font-weight: bold;">Prom. <br> Final</td>
        </tr>
        {foreach $notas as $n}
        <tr style="text-align: center;">
            <td style="text-align: start; height: 18px; padding-left: 3px; font-weight: bold; background-color: #d3d3d3;">{$n.curso}</td>
            <td>
                <label class="{if $n.promedios_bimestres.promedio_bimestre_1 < 11} jalado {else} aprobado {/if}"> {$n.promedios_bimestres.promedio_bimestre_1} </label>
            </td>
            <td>
                <label class="{if $n.promedios_bimestres.promedio_bimestre_2 < 11} jalado {else} aprobado {/if}"> {$n.promedios_bimestres.promedio_bimestre_2} </label>
            </td>
            <td>
                <label class="{if $n.promedios_bimestres.promedio_bimestre_3 < 11} jalado {else} aprobado {/if}"> {$n.promedios_bimestres.promedio_bimestre_3} </label>
            </td>
            <td>
                <label class="{if $n.promedios_bimestres.promedio_bimestre_4 < 11} jalado {else} aprobado {/if}"> {$n.promedios_bimestres.promedio_bimestre_4} </label>
            </td>
            <td>
                <label class="{if $n.promedios_bimestres.promedio_final < 11} jalado {else} aprobado {/if}"> {$n.promedios_bimestres.promedio_final} </label>
            </td>
        </tr>
        {/foreach}        
    </table>

    <table border="1" style="width: 85%; position: fixed; margin-top: 760px; padding-left: 50px; margin-left: auto; margin-right: auto;" cellspacing="0">
        <tr style="text-align: center;">
            <td style="text-align: start; width: 250px; height: 18px; padding-left: 3px; font-weight: bold; background-color: #d3d3d3;">Comportamiento</td>
            <td>
                <label class="{if $promedios_comportamiento.comportamiento_bimestre_1 < 11} jalado {else} aprobado {/if}"> {$promedios_comportamiento.comportamiento_bimestre_1} </label>
            </td>
            <td>
                <label class="{if $promedios_comportamiento.comportamiento_bimestre_2 < 11} jalado {else} aprobado {/if}"> {$promedios_comportamiento.comportamiento_bimestre_2} </label>
            </td>
            <td>
                <label class="{if $promedios_comportamiento.comportamiento_bimestre_3 < 11} jalado {else} aprobado {/if}"> {$promedios_comportamiento.comportamiento_bimestre_3} </label>
            </td>
            <td>
                <label class="{if $promedios_comportamiento.comportamiento_bimestre_4 < 11} jalado {else} aprobado {/if}"> {$promedios_comportamiento.comportamiento_bimestre_4} </label>
            </td>
            <td>
                <label class="{if $promedios_comportamiento.promedio_comportamiento_final < 11} jalado {else} aprobado {/if}"> {$promedios_comportamiento.promedio_comportamiento_final} </label>
            </td>
            
        </tr>
    </table>

    <table border="1" style="width: 85%; position: fixed; margin-top: 790px; padding-left: 50px; margin-left: auto; margin-right: auto;" cellspacing="0">
        <tr>
            <td colspan="6" style="text-align: center; background-color: #d3d3d3; font-weight: bold;">RESUMEN DE ASISTENCIA</td>
        </tr>
        <tr>
            <td style="background-color: #d3d3d3; font-weight: bold; padding-left: 3px;">BIMESTRE</td>
            <td style="text-align: center; font-weight: bold; background-color: #d3d3d3;">A</td>
            <td style="text-align: center; font-weight: bold; background-color: #d3d3d3;">F</td>
            <td style="text-align: center; font-weight: bold; background-color: #d3d3d3;">T</td>
            <td style="text-align: center; font-weight: bold; background-color: #d3d3d3;">F.J.</td>
            <td style="text-align: center; font-weight: bold; background-color: #d3d3d3;">T.J.</td>
        </tr>
        <tr style="text-align: center;">
            <td style="background-color: #d3d3d3; font-weight: bold; text-align: start; padding-left: 3px; height: 18px;">1er Bimestre</td>
            <td>{$asistencia_alumno.primer_bimestre.asistencias}</td>
            <td>{$asistencia_alumno.primer_bimestre.faltas}</td>
            <td>{$asistencia_alumno.primer_bimestre.tardanzas}</td>
            <td>{$asistencia_alumno.primer_bimestre.faltas_justificadas}</td>
            <td>{$asistencia_alumno.primer_bimestre.tardanzas_justificadas}</td>            
        </tr>
        <tr style="text-align: center;">
            <td style="background-color: #d3d3d3; font-weight: bold; text-align: start; padding-left: 3px; height: 18px;">2do Bimestre</td>
            <td>{$asistencia_alumno.segundo_bimestre.asistencias}</td>
            <td>{$asistencia_alumno.segundo_bimestre.faltas}</td>
            <td>{$asistencia_alumno.segundo_bimestre.tardanzas}</td>
            <td>{$asistencia_alumno.segundo_bimestre.faltas_justificadas}</td>
            <td>{$asistencia_alumno.segundo_bimestre.tardanzas_justificadas}</td>            
        </tr>
        <tr style="text-align: center;">
            <td style="background-color: #d3d3d3; font-weight: bold; text-align: start; padding-left: 3px; height: 18px;">3er Bimestre</td>
            <td>{$asistencia_alumno.tercer_bimestre.asistencias}</td>
            <td>{$asistencia_alumno.tercer_bimestre.faltas}</td>
            <td>{$asistencia_alumno.tercer_bimestre.tardanzas}</td>
            <td>{$asistencia_alumno.tercer_bimestre.faltas_justificadas}</td>
            <td>{$asistencia_alumno.tercer_bimestre.tardanzas_justificadas}</td>            
        </tr>
        <tr style="text-align: center;">
            <td style="background-color: #d3d3d3; font-weight: bold; text-align: start; padding-left: 3px; height: 18px;">4to Bimestre</td>
            <td>{$asistencia_alumno.cuarto_bimestre.asistencias}</td>
            <td>{$asistencia_alumno.cuarto_bimestre.faltas}</td>
            <td>{$asistencia_alumno.cuarto_bimestre.tardanzas}</td>
            <td>{$asistencia_alumno.cuarto_bimestre.faltas_justificadas}</td>
            <td>{$asistencia_alumno.cuarto_bimestre.tardanzas_justificadas}</td>            
        </tr>
        <tr style="text-align: center;">
            <td style="background-color: #d3d3d3; font-weight: bold; text-align: start; padding-left: 3px; height: 18px;">TOTAL</td>
            <td>{$asistencia_alumno.totales.asistencias}</td>
            <td>{$asistencia_alumno.totales.faltas}</td>
            <td>{$asistencia_alumno.totales.tardanzas}</td>
            <td>{$asistencia_alumno.totales.faltas_justificadas}</td>
            <td>{$asistencia_alumno.totales.tardanzas_justificadas}</td>
        </tr>
    </table>

    <table style="width: 85%; position: fixed; margin-top: 940px; float: right;">
        <tr>
            <td>Recomendaciones:</td>
        </tr>        
        <tr>
            <td>
                ...................................................................................................................................................................................................
            </td>
        </tr>
        <tr>
            <td>
                ...................................................................................................................................................................................................
            </td>
        </tr>
        <tr>
            <td>
                ...................................................................................................................................................................................................
            </td>
        </tr>
    </table>

    <table style="width: 85%; position: fixed; margin-top: 1040px; float: right;">
        <tr>
            <td style="text-align: center;">
                _________________________________________ <br>
                LIC. AURORA BARRENA PALACIOS<br>
                DIRECTORA
            </td>
        </tr>
    </table>




</body>

</html>