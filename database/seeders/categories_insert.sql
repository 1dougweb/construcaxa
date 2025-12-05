-- SQL INSERT para categorias de produtos
-- Execute este script após criar a tabela categories

INSERT INTO `categories` (`name`, `description`, `sku_prefix`, `attributes_schema`, `parent_id`, `created_at`, `updated_at`) VALUES
-- CIMENTOS E ARGAMASSAS
('Cimento', 'Cimentos para construção civil', 'CIM', '{"tipo":["CP I","CP II","CP III","CP IV","CP V-ARI"],"embalagem":["Saco 50kg","Big Bag 1t"],"marca":["Votorantim","Cimpor","Itambé","Outra"]}', NULL, NOW(), NOW()),
('Argamassas e Rejunte', 'Argamassas colantes, rejuntes e impermeabilizantes', 'ARG', '{"tipo":["Colante","Rejunte","Impermeabilizante","Chapisco"],"aplicacao":["Piso","Parede","Fachada","Piscina"],"embalagem":["Saco 20kg","Balde 20kg","Saco 40kg"]}', NULL, NOW(), NOW()),
('Cal e Gesso', 'Cal hidratada e gesso para acabamento', 'CGE', '{"tipo":["Cal Hidratada","Cal Virgem","Gesso"],"embalagem":["Saco 20kg","Saco 40kg"]}', NULL, NOW(), NOW()),

-- FERRO E AÇO
('Ferro para Construção', 'Barras de ferro e vergalhões para estruturas', 'FER', '{"bitola":["5mm","6mm","8mm","10mm","12.5mm","16mm","20mm","25mm"],"comprimento":["6m","12m"],"tipo":["CA-50","CA-60"]}', NULL, NOW(), NOW()),
('Arame e Tela', 'Arames e telas para construção', 'ARA', '{"tipo":["Arame Farpado","Arame Galvanizado","Tela Soldada","Tela de Alambrado"],"bitola":["16","18","20","22"]}', NULL, NOW(), NOW()),
('Estruturas Metálicas', 'Perfis metálicos, vigas e estruturas em aço', 'EST', '{"tipo":["Viga I","Perfil U","Perfil L","Perfil Tubular"],"material":["Aço Carbono","Aço Galvanizado","Aço Inox"]}', NULL, NOW(), NOW()),

-- TIJOLOS E BLOCOS
('Tijolos', 'Tijolos cerâmicos e maciços', 'TIJ', '{"tipo":["Baiano","Macico","Furrado","Modular"],"dimensao":["9x19x19cm","11x19x19cm","Outra"]}', NULL, NOW(), NOW()),
('Blocos de Concreto', 'Blocos de concreto para alvenaria estrutural', 'BLO', '{"tipo":["Estrutural","Vedação"],"dimensao":["14x19x39cm","19x19x39cm","Outra"]}', NULL, NOW(), NOW()),
('Blocos de Vidro', 'Blocos de vidro para iluminação natural', 'BLV', '{"dimensao":["19x19cm","14x19cm"],"cor":["Transparente","Fosco","Colorido"]}', NULL, NOW(), NOW()),

-- AREIA E PEDRA
('Areia', 'Areia para construção', 'ARE', '{"tipo":["Fina","Média","Grossa","Lavada"],"embalagem":["m³","Saco 20kg"]}', NULL, NOW(), NOW()),
('Pedra e Brita', 'Pedras e britas para construção', 'PED', '{"tipo":["Brita 0","Brita 1","Brita 2","Pedra Rachão","Pó de Pedra"],"embalagem":["m³","Saco 20kg"]}', NULL, NOW(), NOW()),

-- TELHAS E COBERTURAS
('Telhas', 'Telhas para cobertura', 'TEL', '{"tipo":["Cerâmica","Metálica","Fibrocimento","PVC"],"modelo":["Portuguesa","Romana","Colonial","Americana"],"cor":["Vermelha","Marrom","Verde","Cinza"]}', NULL, NOW(), NOW()),
('Calhas e Rufos', 'Calhas, rufos e peças para cobertura', 'CRU', '{"tipo":["Calha","Rufo","Tirante","Colete"],"material":["Galvanizado","Alumínio","PVC"]}', NULL, NOW(), NOW()),

-- PISOS E REVESTIMENTOS
('Cerâmicas e Porcelanatos', 'Pisos e revestimentos cerâmicos', 'CER', '{"tipo":["Cerâmica","Porcelanato","Pastilha"],"dimensao":["30x30cm","45x45cm","60x60cm","60x120cm"],"acabamento":["Polido","Acetinado","Esmaltado"]}', NULL, NOW(), NOW()),
('Pedras Naturais', 'Pedras naturais para revestimento', 'PNT', '{"tipo":["Granito","Mármore","Quartzito","Ardósia","Calcário"],"acabamento":["Polido","Bruto","Escovado"]}', NULL, NOW(), NOW()),
('Pisos Laminados e Vinílicos', 'Pisos sintéticos e laminados', 'PLV', '{"tipo":["Laminado","Vinílico","PVC"],"classe_uso":["AC3","AC4","AC5"]}', NULL, NOW(), NOW()),

-- TINTAS E VERNIZES
('Tintas', 'Tintas para parede, madeira e metal', 'TIN', '{"tipo":["Acrílica","PVA","Esmalte","Epóxi"],"aplicacao":["Parede","Madeira","Metal","Piscina"],"embalagem":["3.6L","18L","200L"]}', NULL, NOW(), NOW()),
('Vernizes e Impermeabilizantes', 'Vernizes, seladores e impermeabilizantes', 'VER', '{"tipo":["Verniz","Selador","Impermeabilizante","Massa Corrida"],"aplicacao":["Madeira","Concreto","Terraço","Laje"]}', NULL, NOW(), NOW()),

-- INSTALAÇÕES ELÉTRICAS
('Fios e Cabos Elétricos', 'Fios e cabos para instalação elétrica', 'FIO', '{"bitola":["1.5mm²","2.5mm²","4mm²","6mm²","10mm²"],"tipo":["Flexível","Rígido"],"tensao":["750V","1000V"]}', NULL, NOW(), NOW()),
('Disjuntores e Quadros', 'Disjuntores, quadros e componentes elétricos', 'DIS', '{"tipo":["Disjuntor Monopolar","Disjuntor Bipolar","Quadro","IDR"],"corrente":["10A","16A","20A","25A","32A"]}', NULL, NOW(), NOW()),
('Tomadas e Interruptores', 'Tomadas, interruptores e acessórios elétricos', 'TOM', '{"tipo":["Tomada","Interruptor","Dimmer","Campainha"],"tensao":["110V","220V","Bivolt"]}', NULL, NOW(), NOW()),

-- INSTALAÇÕES HIDRÁULICAS
('Tubos e Conexões PVC', 'Tubos e conexões de PVC para esgoto', 'PVC', '{"diametro":["50mm","75mm","100mm","150mm"],"tipo":["Esgoto","Água","Ventilação"]}', NULL, NOW(), NOW()),
('Tubos e Conexões PPR', 'Tubos e conexões PPR para água', 'PPR', '{"diametro":["20mm","25mm","32mm","40mm","50mm"],"pressao":["6 bar","10 bar"]}', NULL, NOW(), NOW()),
('Tubos e Conexões Metálicas', 'Tubos e conexões de cobre, galvanizado e inox', 'TUB', '{"material":["Cobre","Galvanizado","Inox"],"diametro":["1/2 pol","3/4 pol","1 pol","1 1/4 pol","1 1/2 pol"]}', NULL, NOW(), NOW()),
('Louças e Metais', 'Louças sanitárias e metais para banheiro', 'LOU', '{"tipo":["Vaso","Caixa Acoplada","Bacia","Chuveiro","Torneira"],"material":["Inox","Cromado","Porcelana"]}', NULL, NOW(), NOW()),

-- MADEIRA
('Madeiras Brutas', 'Madeiras em toras e serradas', 'MAD', '{"tipo":["Eucalipto","Pinus","Cedro","Ipê","Peroba"],"dimensao":["2x4cm","3x6cm","5x10cm","Tora"]}', NULL, NOW(), NOW()),
('Chapas de Madeira', 'Compensados, MDF, MDP e OSB', 'CHP', '{"tipo":["Compensado","MDF","MDP","OSB","Aglomerado"],"dimensao":["122x244cm","152x244cm"],"espessura":["3mm","6mm","9mm","12mm","15mm","18mm"]}', NULL, NOW(), NOW()),
('Portas e Janelas', 'Portas, janelas e esquadrias', 'POR', '{"tipo":["Porta","Janela","Basculante","Correr"],"material":["Madeira","Alumínio","PVC","Ferro"]}', NULL, NOW(), NOW()),

-- FERRAMENTAS E EQUIPAMENTOS
('Ferramentas Manuais', 'Ferramentas manuais para construção', 'FMA', '{"tipo":["Marreta","Enxada","Pá","Enxó","Colher de Pedreiro"],"material":["Aço","Fibra de Vidro"]}', NULL, NOW(), NOW()),
('Ferramentas Elétricas', 'Ferramentas elétricas e pneumáticas', 'FEI', '{"tipo":["Furadeira","Parafusadeira","Serra","Esmerilhadeira"],"voltagem":["110V","220V","Bateria"]}', NULL, NOW(), NOW()),

-- SEGURANÇA E PROTEÇÃO
('EPIs', 'Equipamentos de proteção individual', 'EPI', '{"tipo":["Capacete","Óculos","Luvas","Botas","Protetor Auricular"],"tamanho":["P","M","G","GG","Único"]}', NULL, NOW(), NOW()),
('Sinalização e Segurança', 'Placas, fitas e equipamentos de sinalização', 'SIG', '{"tipo":["Placa","Fita","Cone","Cavalete","Barreira"]}', NULL, NOW(), NOW()),

-- DIVERSOS
('Vedação e Isolamento', 'Espumas, silicones e materiais de vedação', 'VED', '{"tipo":["Espuma Expansiva","Silicone","Lã de Vidro","Manta Asfáltica"],"aplicacao":["Janelas","Portas","Telhado","Paredes"]}', NULL, NOW(), NOW()),
('Formas e Escoramentos', 'Formas de madeira e escoramentos metálicos', 'FES', '{"tipo":["Tábua","Sarrafos","Escoramento","Cimbramento"]}', NULL, NOW(), NOW()),
('Forros e Drywall', 'Chapas para forro e divisórias', 'FRY', '{"tipo":["Drywall","Lã de Rocha","PVC","Madeira"],"espessura":["6mm","9mm","12mm","15mm"]}', NULL, NOW(), NOW()),
('Impermeabilizantes', 'Membranas e produtos para impermeabilização', 'IMP', '{"tipo":["Membrana","Tinta","Manta","Betume"],"aplicacao":["Laje","Piscina","Fundação","Cobertura"]}', NULL, NOW(), NOW()),
('Vidros e Espelhos', 'Vidros temperados, laminados e espelhos', 'VID', '{"tipo":["Temperado","Laminado","Comum","Espelho"],"espessura":["3mm","4mm","5mm","6mm","8mm","10mm"]}', NULL, NOW(), NOW()),
('Componentes de Concreto', 'Aditivos, fibras e componentes para concreto', 'ADT', '{"tipo":["Plastificante","Acelerador","Retardador","Fibras"],"embalagem":["Litro","Saco 1kg","Saco 5kg"]}', NULL, NOW(), NOW());

