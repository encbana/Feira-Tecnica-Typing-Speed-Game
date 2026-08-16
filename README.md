# Typing Rush

Jogo de teste de velocidade de digitação, desenvolvido em Unity 2D e integrado a um site com ranking online e sistema de feedback. Desenvolvido para a Feira Técnica 2025 do Colégio Univap.

![Unity](https://img.shields.io/badge/Unity-2D-000000?logo=unity&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-XAMPP-4479A1?logo=mysql&logoColor=white)

---

## Capturas de tela

![Jogo](./screenshots/jogo.png)
![Ranking](./screenshots/ranking.png)
![Feedback](./screenshots/feedback.png)

## Funcionalidades

- 4 níveis de dificuldade (Fácil, Médio, Difícil, Insano)
- Sistema de multiplicador de pontuação e barra de habilidade especial
- Modo bônus ("Hora dos Números"), desbloqueado ao completar uma partida sem erros
- Ranking online com os melhores tempos e pontuações, persistido em banco de dados
- Sistema de feedback com comentário e avaliação por estrelas
- Site com navegação por abas (Início, Jogo, Ranking, Feedback, Equipe)

## Tecnologias

| Camada     | Tecnologias                              |
| ---------- | ------------------------------------------ |
| Jogo       | Unity 2D (build WebGL)                     |
| Backend    | PHP, mysqli (prepared statements)          |
| Banco      | MySQL / MariaDB                            |
| Frontend   | HTML, CSS, JavaScript                      |

## Estrutura de pastas

```
JogoTeclado/
├── assets/                    # Imagens do site
├── jogo/
│   ├── Build/                 # Build WebGL do Unity
│   └── TemplateData/
├── index.html                 # Página principal (navegação por abas)
├── ranking.php                 # Listagem e paginação do ranking
├── feedback.php                # Registro e listagem de avaliações
├── style.css / star.css
└── SQL PRA USAR.txt            # Script de criação do banco
```

## Requisitos

- PHP 8 com a extensão `mysqli`
- MySQL ou MariaDB
- XAMPP (ou outro ambiente Apache + PHP + MySQL)
- Navegador com suporte a WebGL para rodar o jogo

## Como rodar

1. **Copie o projeto para o servidor local**

   Copie a pasta do projeto para `C:\xampp\htdocs\`.

2. **Inicie o Apache e o MySQL**

   Pelo painel do XAMPP.

3. **Crie o banco de dados**

   Acesse `http://localhost/phpmyadmin`, abra a aba SQL e execute o conteúdo de `SQL PRA USAR.txt`.

4. **Acesse a aplicação**

   `http://localhost/JogoTeclado/index.html`

---

Desenvolvido para a Feira Técnica 2025 — Colégio Univap.
