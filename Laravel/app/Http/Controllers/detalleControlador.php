<?php

namespace App\Http\Controllers;

use App\Models\detalleModelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class detalleControlador extends Controller
{
    public function index()
    {
        $detalle = detalleModelo::all();
        if ($detalle->isEmpty()) {
            return response()->json([
                'message' => 'No hay detalles registrados',
                'status' => 200
            ], 200); 
        }
        return response()->json($detalle, 200);
    }

    public function store(Request $request) 
    {
        $validacion = Validator::make($request->all(), [
            'cantidadProductoPedido' => 'required|int',
            'precioUnidadProducto' => 'required|numeric',
            'subtotalPedidoProducto' => 'required|numeric',
            'id_Pedido_FK'  => 'required|int',
            'prod_Codigo_FK'  => 'required|int',
            'metodoPago'  => 'required|min:4|max:11'
        ]);

        if ($validacion->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validacion->errors(),
                'status' => 400 
            ], 400);
        }

        $detalle = detalleModelo::create([
            'cantidadProductoPedido' => $request->cantidadProductoPedido,
            'precioUnidadProducto' => $request->precioUnidadProducto,
            'subtotalPedidoProducto' => $request->subtotalPedidoProducto,
            'id_Pedido_FK'  => $request->id_Pedido_FK,
            'prod_Codigo_FK'  => $request->prod_Codigo_FK,
            'metodoPago'  => $request->metodoPago
        ]);

        if (!$detalle) {
            return response()->json([
                'message' => 'Error al registrar el detalle', 
                'status' => 500
            ], 500);
        }

        return response()->json([
            'pedido' => $detalle,
            'status' => 201
        ], 201);
    }

    public function show($id_Pedido_FK){
        $detalle = detalleModelo::find($id_Pedido_FK);
        if(!$detalle){
            $data=[
            'message'=>'El detalle no existe',
            'status'=> 404
            ];
            return response()->json($data,404);
        }
        $data=[
            'detalle'=>$detalle,
            'status'=>200
        ];
        return response()->json($data,200);
    }
}