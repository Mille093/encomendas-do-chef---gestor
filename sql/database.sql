-- database.sql (Encomendas do Chef - versão gestor)
CREATE DATABASE IF NOT EXISTS encomendas_chef_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE encomendas_chef_db;

-- usuarios (clientes)
CREATE TABLE IF NOT EXISTS usuarios (
    usu_id INT AUTO_INCREMENT PRIMARY KEY,
    usu_nome VARCHAR(100) NOT NULL,
    usu_email VARCHAR(100) NOT NULL UNIQUE,
    usu_senha VARCHAR(255) NOT NULL,
    usu_telefone VARCHAR(20),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- clientes
CREATE TABLE IF NOT EXISTS clientes (
    cli_codigo INT AUTO_INCREMENT PRIMARY KEY,
    cli_nome VARCHAR(100) NOT NULL,
    cli_cep VARCHAR(10),
    cli_rua VARCHAR(255),
    cli_numero VARCHAR(20),
    cli_bairro VARCHAR(100),
    cli_cidade VARCHAR(100),
    cli_estado VARCHAR(50),
    cli_telefone VARCHAR(20),
    cli_status ENUM('bom','medio','ruim') DEFAULT 'bom',
    cli_email VARCHAR(100) UNIQUE NOT NULL,
    usuario_id INT UNIQUE,
    CONSTRAINT fk_cliente_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(usu_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- categorias
CREATE TABLE IF NOT EXISTS categorias (
    cat_codigo INT AUTO_INCREMENT PRIMARY KEY,
    cat_nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- produtos
CREATE TABLE IF NOT EXISTS produtos (
    prod_codigo INT AUTO_INCREMENT PRIMARY KEY,
    prod_nome VARCHAR(150) NOT NULL,
    prod_descricao TEXT,
    prod_preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    prod_ativo TINYINT(1) DEFAULT 1,
    prod_imagem VARCHAR(255),
    cat_codigo INT,
    FOREIGN KEY (cat_codigo) REFERENCES categorias(cat_codigo) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    ped_numero INT AUTO_INCREMENT PRIMARY KEY,
    ped_data_elaboracao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cli_codigo INT NOT NULL,
    ped_valor_total DECIMAL(10,2) DEFAULT 0.00,
    ped_status ENUM('pendente','em_preparacao','pronto','entregue','cancelado') DEFAULT 'pendente',
    ped_endereco_entrega VARCHAR(255),
    ped_tipo_entrega ENUM('retirada','entrega') DEFAULT 'retirada',
    FOREIGN KEY (cli_codigo) REFERENCES clientes(cli_codigo) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- itens_pedido
CREATE TABLE IF NOT EXISTS itens_pedido (
    ped_numero INT NOT NULL,
    prod_codigo INT NOT NULL,
    itp_quantidade_comprada INT NOT NULL,
    itp_preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (ped_numero, prod_codigo),
    FOREIGN KEY (ped_numero) REFERENCES pedidos(ped_numero) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (prod_codigo) REFERENCES produtos(prod_codigo) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- avaliacoes
CREATE TABLE IF NOT EXISTS avaliacoes (
    avl_id INT AUTO_INCREMENT PRIMARY KEY,
    prod_codigo INT,
    cli_codigo INT,
    nota TINYINT(1) NOT NULL,
    comentario TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prod_codigo) REFERENCES produtos(prod_codigo) ON DELETE SET NULL,
    FOREIGN KEY (cli_codigo) REFERENCES clientes(cli_codigo) ON DELETE SET NULL
) ENGINE=InnoDB;

-- gestores (usuários da área do gestor/admin)
CREATE TABLE IF NOT EXISTS gestores (
    gst_id INT AUTO_INCREMENT PRIMARY KEY,
    gst_nome VARCHAR(100) NOT NULL,
    gst_email VARCHAR(100) NOT NULL UNIQUE,
    gst_senha VARCHAR(255) NOT NULL,
    gst_role ENUM('admin','operador') DEFAULT 'operador',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- promocoes
CREATE TABLE IF NOT EXISTS promocoes (
    prm_id INT AUTO_INCREMENT PRIMARY KEY,
    prm_nome VARCHAR(150),
    prm_descricao TEXT,
    prm_tipo ENUM('percentual','valor') DEFAULT 'percentual',
    prm_valor DECIMAL(10,2),
    prm_data_inicio DATE,
    prm_data_fim DATE,
    ativo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Trigger para atualizar ped_valor_total (same logic)
DELIMITER //
CREATE TRIGGER trg_itens_pedido_after_insert
AFTER INSERT ON itens_pedido
FOR EACH ROW
BEGIN
    UPDATE pedidos
    SET ped_valor_total = (
        SELECT COALESCE(SUM(itp_quantidade_comprada * itp_preco_unitario),0)
        FROM itens_pedido
        WHERE ped_numero = NEW.ped_numero
    )
    WHERE ped_numero = NEW.ped_numero;
END;
//
CREATE TRIGGER trg_itens_pedido_after_update
AFTER UPDATE ON itens_pedido
FOR EACH ROW
BEGIN
    UPDATE pedidos
    SET ped_valor_total = (
        SELECT COALESCE(SUM(itp_quantidade_comprada * itp_preco_unitario),0)
        FROM itens_pedido
        WHERE ped_numero = NEW.ped_numero
    )
    WHERE ped_numero = NEW.ped_numero;
END;
//
CREATE TRIGGER trg_itens_pedido_after_delete
AFTER DELETE ON itens_pedido
FOR EACH ROW
BEGIN
    UPDATE pedidos
    SET ped_valor_total = (
        SELECT COALESCE(SUM(itp_quantidade_comprada * itp_preco_unitario), 0)
        FROM itens_pedido
        WHERE ped_numero = OLD.ped_numero
    )
    WHERE ped_numero = OLD.ped_numero;
END;
//
DELIMITER ;

-- Dados iniciais
INSERT IGNORE INTO categorias (cat_nome) VALUES
('Bolos'), ('Tortas Salgadas'), ('Salgados'), ('Pães Artesanais');

INSERT IGNORE INTO produtos (prod_nome, prod_preco, cat_codigo, prod_descricao) VALUES
('Bolo de Cenoura com Chocolate', 45.00, 1, 'Bolo fofo com cobertura de chocolate'),
('Torta de Limão Merengada', 55.00, 2, 'Torta cítrica com merengue crocante'),
('Torta de Frango Cremosa', 60.00, 3, 'Recheio cremoso de frango'),
('Mini Coxinha (cento)', 35.00, 3, 'Coxinha tradicional - cento'),
('Pão Integral Fermentação Natural', 18.00, 4, 'Pão integral caseiro');

-- Admin inicial (senha: 123456)
INSERT IGNORE INTO gestores (gst_nome, gst_email, gst_senha, gst_role)
VALUES ('Administrador', 'admin@encomendaschef.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
