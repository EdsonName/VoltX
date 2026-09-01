package com.volx.controller;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/servicos")
@CrossOrigin(origins = "*")
public class ServicoController {

    @GetMapping
    public ResponseEntity<?> listarServicos() {
        return ResponseEntity.ok("Lista de serviços");
    }

    @GetMapping("/{id}")
    public ResponseEntity<?> obterServico(@PathVariable Long id) {
        return ResponseEntity.ok("Serviço " + id);
    }

    @PostMapping
    public ResponseEntity<?> criarServico() {
        return ResponseEntity.status(201).body("Serviço criado");
    }

    @PutMapping("/{id}")
    public ResponseEntity<?> atualizarServico(@PathVariable Long id) {
        return ResponseEntity.ok("Serviço " + id + " atualizado");
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> deletarServico(@PathVariable Long id) {
        return ResponseEntity.noContent().build();
    }
}
