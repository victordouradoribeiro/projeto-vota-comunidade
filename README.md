# Vota Comunidade

Sistema de votação eletrônica para condomínios desenvolvido em Laravel 11, permitindo que administradores, síndicos e moradores participem de forma organizada e transparente do processo democrático condominial.

## 📋 Descrição do Projeto

O **Vota Comunidade** é uma plataforma web que digitaliza e facilita o processo de votações em condomínios, oferecendo três perfis distintos de usuários com funcionalidades específicas para cada tipo de acesso.

### Funcionalidades Principais

- **Sistema de Autenticação Multi-perfil**: Administrador, Síndico e Morador
- **Gestão de Condomínios**: Cadastro e administração completa de condomínios
- **Gestão de Usuários**: Controle de moradores, síndicos e suas aprovações
- **Sistema de Votações**: Criação, edição e gerenciamento de pautas de votação
- **Resultados em Tempo Real**: Visualização de resultados das votações
- **Interface Responsiva**: Adaptável para diferentes dispositivos

### Perfis de Usuário

#### 🔧 Administrador
- Gerenciar condomínios (criar, editar, remover)
- Gerenciar síndicos e moradores
- Aprovar/rejeitar cadastros de usuários
- Visualizar resultados de todas as votações
- Acesso completo ao sistema

#### 🏢 Síndico
- Criar e gerenciar votações do seu condomínio
- Gerenciar moradores do condomínio
- Visualizar resultados das votações
- Controlar pautas ativas e encerradas

#### 🏠 Morador
- Votar nas pautas disponíveis
- Visualizar resultados das votações
- Acessar histórico de votações encerradas
- Participar do processo democrático condominial

## 🎯 Arquitetura do Sistema

### Estrutura Geral

```
/projeto-vota-comunidade
│
├── /admin
│    ├── adicionar-condominio.php
│    ├── adicionar-morador.php
│    ├── adicionar-sindico.php
│    ├── auth.php                 
│    ├── dashboard.php
│    ├── editar-condominio.php
│    ├── editar-morador.php
│    ├── editar-sindico.php
│    ├── gerenciar-condominios.php
│    ├── gerenciar-moradores.php
│    ├── gerenciar-sindicos.php
│    └── resultados.php
│
├── /assets
│    ├── /css
│    │    └── styles.css
│    └── /js
│
├── /config
│    └── conexao.php              
│
├── /includes
│    ├── footer.php                
│    ├── header.php                
│    ├── navbar-admin.php          
│    ├── navbar-morador.php        
│    └── navbar-sindico.php        
│
├── /morador
│    ├── auth.php                 
│    ├── dashboard.php
│    ├── detalhes-votacao.php
│    ├── minha-conta.php
│    ├── resultados.php
│    └── votacoes.php
│
├── /php_action                   
│    ├── approve-morador.php
│    ├── approve-sindico.php
│    ├── create-condominio.php
│    ├── create-morador.php
│    ├── create-sindico.php
│    ├── create-votacao.php
│    ├── delete-condominio.php
│    ├── delete-morador.php
│    ├── delete-sindico.php
│    ├── encerrar-votacao.php
│    ├── read-condominios.php
│    ├── read-moradores.php
│    ├── read-resultados-sindico.php
│    ├── read-resultados.php
│    ├── read-sindicos.php
│    ├── read-votacoes-morador.php
│    ├── read-votacoes.php
│    ├── reject-morador.php
│    ├── reject-sindico.php
│    ├── registrar-voto.php
│    ├── update-condominio.php
│    ├── update-morador.php
│    ├── update-morador-myaccount.php
│    ├── update-sindico-myaccount.php
│    └── update-votacao.php
│
├── /public
│    ├── forgot-password.php
│    ├── login.php
│    ├── logout.php
│    └── register.php
│
├── /sindico
│    ├── auth.php                  
│    ├── criar-votacao.php
│    ├── dashboard.php
│    ├── detalhes-votacao.php
│    ├── editar-votacao.php
│    ├── encerrar-votacao.php
│    ├── gerenciar-votacoes.php
│    ├── minha-conta.php
│    └── resultados.php
│
└── README.md
```

## 👥 Contribuição

Para contribuir com o projeto:

1. Fork o repositório
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 📞 Suporte

Para dúvidas, sugestões ou reportar problemas de acessibilidade, entre em contato através das issues do GitHub.

---
