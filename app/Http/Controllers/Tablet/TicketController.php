<?php

namespace App\Http\Controllers\Tablet;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class TicketController extends Controller
{
    public function imprimirCocina(Request $request)
    {
        $titulo = $request->titulo;
        $mozo = $request->mozo;
        $prod = $request->prod;
        $nro_print = $request->nro_print;
        $nrocmd = $request->nrocmd;
        try {
            $date_time = Carbon::now('America/Lima')->toDateTimeString();
            // Configuracion de conexion
            $nombreImpresora = "Cocina";
            $connector = new NetworkPrintConnector("192.168.1.99", 9100);
            $impresora = new Printer($connector);
            // Cabecera de ticket
            $impresora->setDoubleStrike(false);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(4, 3);
            $impresora->setFont(Printer::FONT_B);
            $impresora->text($titulo . "\n");
            $impresora->setTextSize(2, 1);
            $impresora->text("IMPRESION N°: " . $nro_print ." - CMD # ". $nrocmd ."\n");
            $impresora->feed(1);

           

            $asterisco = "";
            $linea = "";
            for ($i = 0; $i < 32; $i++) {
                $asterisco = $asterisco . '*';
                $linea = $linea . '-';
            }

            //Detalle
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            //$impresora->setEmphasis(true);
            $impresora->text("MOZO: " . $mozo . "\n");
            $impresora->text("Fecha-Hora: " . $date_time . "\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text($linea . "\n");
            $impresora->feed(2);
            $impresora->setTextSize(2, 2);
            //Items
           // $impresora->setEmphasis(false);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($prod as $key => $value) {
                $nombre = $value['name'];
                $nombre = str_replace("�", "ñ", $nombre);
                $cant   =  $value['cant'];
                $impresora->text(new itemCocina($cant . " X", $nombre));
            }
            
            /*$impresora->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($detalles['notas'] as $key => $value) {
                $impresora->text("Nota: " . $value->note . "\n");
                $impresora->feed(1);
            }*/

            $impresora->feed(1);
            $impresora->setTextSize(2, 1);
            $impresora->text($linea . "\n");
            $impresora->feed(1);
            $impresora->cut(Printer::CUT_PARTIAL);
            //$impresora->cut();
            $impresora->close();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Error de ticketera','error'=> $e, 'status' => 0, 'tipo' => 'tikect'], 200);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Error de ticketera','error'=> $e, 'status' => 0, 'tipo' => 'tikect'], 200);
        }
        return response()->json(['msg' => 'OK', 'status' => 1], 200);
    }
    public function imprimirPrecuenta(Request $request)
    {
        $empresa = $request->empresa;
        $cajero = $request->cajero;
        $mozo = $request->mozo;
        $mesas = $request->mesas;
        $prod = $request->prod;
        $sub_total = $request->sub_total;
        $adicionales = $request->adicionales;
        $total = $request->total;
        try {
            $date_time = Carbon::now('America/Lima')->toDateTimeString();
            // Configuracion de conexion
            $connector = new NetworkPrintConnector("192.168.1.99", 9100);
            $impresora = new Printer($connector);
            // Cabecera de ticket
            $impresora->setDoubleStrike(false);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(2, 1);
            $impresora->setFont(Printer::FONT_B);
            $impresora->text($empresa . "\n");
            $impresora->feed(1);
            $impresora->text("Pre - Cuenta\n");
            $impresora->feed(1);

           

            $asterisco = "";
            $linea = "";
            $lineaAlta = "";
            for ($i = 0; $i < 48; $i++) {
                $asterisco = $asterisco . '*';
                $linea = $linea . '_';
                $lineaAlta = $lineaAlta . '¯';
            }

            //Detalle
            
            $impresora->setFont(Printer::FONT_A);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->setTextSize(1, 1);
            $impresora->text("Mozo: " . $mozo ."\n");
            $impresora->feed(1);
            $impresora->text("Fecha-Hora: " . $date_time . "\n");
            $impresora->feed(1);
            $impresora->text("Razon Social: ________________________________\n");
            $impresora->feed(1);
            $impresora->text("RUC: _________________________________________\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            //Items
            // $impresora->setEmphasis(false);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text($linea . "\n");
            $impresora->text(new itemPrecuenta('Cant', 'Descripcion', 'Sub Total'));
            $impresora->text($lineaAlta . "\n");
            foreach ($prod as $key => $value) {
                $nombre = $value['name'];
                $nombre = str_replace("�", "ñ", $nombre);
                $cant   =  $value['cant'];
                $subtotal   =  $value['subtotal'];
                $impresora->text(new itemPrecuenta($cant, $nombre, $subtotal));
            }
            $impresora->setJustification(Printer::JUSTIFY_RIGHT);
            $impresora->text("__________________________________"."\n");
            $impresora->text("Sub Total:       " . $sub_total."\n");
            $impresora->text("Adicionales:       " . $adicionales."\n");
            $impresora->text("__________________________________"."\n");
            $impresora->text("Total Pagar:       " . $total."\n");
            $impresora->feed(2);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setEmphasis(false);
            $impresora->text("Este no es un comprobante de Pago.\n");
            $impresora->text("Por favor canjear en caja si desea factura\n");
            $impresora->text("o ticket\n");
            $impresora->text("Gracias por su gentil preferencia\n");
            $impresora->feed(1);

            /*$impresora->feed(1);
            $impresora->setTextSize(2, 1);
            $impresora->text($linea . "\n");*/
            $impresora->feed(1);
            $impresora->cut(Printer::CUT_PARTIAL);
            //$impresora->cut();
            $impresora->close();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Error de ticketera','error'=> $e, 'status' => 0, 'tipo' => 'tikect'], 200);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Error de ticketera','error'=> $e, 'status' => 0, 'tipo' => 'tikect'], 200);
        }
        return response()->json(['msg' => 'OK', 'status' => 1], 200);
    }
}

class itemCocina
{
    private $name;
    private $cant;

    public function __construct($cant = '', $name = '')
    {
        $this->name = $name;
        $this->cant = $cant;
    }

    public function __toString()
    {
        $rightCols = 27;
        $leftCols = 5;
        $left = str_pad($this->cant, $leftCols);
        $right = str_pad($this->name, $rightCols, ' ', STR_PAD_RIGHT);
        //34
        // DD("$left$right\n");
        return "$left$right\n";
    }
}
class itemPrecuenta
{
    private $cant;
    private $name;
    private $price;

    public function __construct($cant = '', $name = '', $price = '')
    {
        $this->cant = $cant;
        $this->name = $name;
        $this->price = $price;
    }

    public function __toString()
    {
        $rightCols = 9;
        $interCols = 34;
        $leftCols  = 5;
        $left = str_pad($this->cant, $leftCols);
        $inter = str_pad($this->name, $interCols);

        $lengthName = strlen($this->name);
        if ($lengthName > 33) {
            $inter = substr($this->name, 0, 33);
        }

        $right = str_pad($this->price, $rightCols, ' ', STR_PAD_LEFT);
        // DD("$left$right\n");
        return "$left$inter$right\n";
    }
}
