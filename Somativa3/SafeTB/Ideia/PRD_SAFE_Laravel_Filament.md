# PRD.md — SAFE: Sistema de Autorização e Fluxo Escolar

## 1. Nome do Projeto

**SAFE — Sistema de Autorização e Fluxo Escolar**

Sistema web para digitalizar o processo de autorização de entrada e saída de alunos em ambiente escolar, substituindo formulários físicos por um fluxo digital validado pela AQV/responsável interno, professor e portaria.

---

## 2. Objetivo do Projeto

Criar um protótipo funcional em **Laravel** utilizando **Filament Admin Panel** para controlar autorizações escolares de entrada e saída de alunos, com validação por professor, confirmação pela portaria e simulação de notificações automáticas para responsáveis via e-mail e WhatsApp simulado.

O sistema deve registrar todo o histórico de autorização, validação, movimentação real do aluno, faltas por aula e logs de notificação.

---

## 3. Contexto do Problema

Atualmente, o processo de entrada e saída de alunos depende de autorizações manuais em papel, o que pode gerar:

- Perda de documentos físicos.
- Falha na comunicação entre AQV, professor e portaria.
- Ausência de histórico centralizado.
- Dificuldade de rastrear quem autorizou ou validou.
- Falta de registro do horário real de entrada ou saída.
- Falta de notificação automática ao responsável.
- Baixa segurança no fluxo escolar.

O SAFE resolve esse problema com um painel digital, controle por perfis de usuário, registros auditáveis e notificações simuladas.

---

## 4. Stack Técnica Obrigatória

O projeto deve ser construído com:

- **Laravel**
- **Filament Admin Panel**
- **PHP**
- **MySQL ou PostgreSQL**
- **Laravel Migrations**
- **Laravel Models e Eloquent Relationships**
- **Laravel Notifications**
- **Laravel Events e Listeners**
- **Laravel Policies ou Gates**
- **Mailpit para teste de e-mails**
- **Log::info para simular WhatsApp**
- **Blade/Livewire conforme padrão do Filament**

---

## 5. Tipo de Aplicação

A aplicação será um sistema web administrativo com painel interno.

O foco é um MVP funcional, não um sistema público aberto.

---

## 6. Perfis de Usuário

Criar controle de acesso por perfil.

### Perfis necessários

| Perfil | Permissões principais |
|---|---|
| `admin` | Acesso total ao sistema |
| `aqv` | Pode abrir autorizações e consultar histórico |
| `professor` | Pode visualizar e aprovar/recusar autorizações vinculadas a ele |
| `portaria` | Pode validar entrada/saída real do aluno |
| `coordenacao` | Pode consultar dashboards, relatórios e histórico |

---

## 7. Módulos do Sistema

A aplicação deve possuir os seguintes módulos:

1. Dashboard
2. Alunos
3. Professores
4. Responsáveis
5. Cursos
6. Turmas
7. Horários das aulas
8. Autorizações
9. Aulas impactadas pela autorização
10. Movimentações reais do aluno
11. Logs de notificação
12. Auditoria básica de ações

---

## 8. Dashboard

O dashboard do Filament deve exibir cards/widgets com:

- Total de autorizações criadas hoje.
- Total de autorizações aguardando professor.
- Total de autorizações aguardando portaria.
- Total de entradas registradas hoje.
- Total de saídas registradas hoje.
- Total de alunos com falta hoje.
- Últimas movimentações registradas.
- Últimas notificações simuladas.

---

## 9. Fluxo Principal — Saída do Aluno

1. Usuário AQV ou autorizado abre uma autorização de saída.
2. Seleciona aluno, professor, data, horário previsto e motivo.
3. O sistema preenche turma e curso automaticamente a partir do aluno.
4. A autorização recebe status `aguardando_professor`.
5. Professor visualiza a autorização no painel.
6. Professor aprova ou recusa.
7. Se aprovada, o status muda para `aguardando_portaria`.
8. Portaria confirma a saída no momento real.
9. Sistema registra data e hora exata da saída.
10. Sistema cria registro em `student_movements`.
11. Sistema dispara evento `StudentMovementValidated`.
12. Sistema envia e-mail de teste via Mailpit.
13. Sistema registra WhatsApp simulado usando `Log::info`.
14. Sistema salva os logs em `notification_logs`.
15. Autorização é concluída.

---

## 10. Fluxo Principal — Entrada do Aluno

1. AQV ou portaria registra uma autorização de entrada.
2. Seleciona aluno, professor, data, horário previsto e motivo.
3. O sistema identifica a turma e curso do aluno.
4. Professor valida ciência da entrada.
5. Portaria confirma a entrada no momento real.
6. Sistema calcula se houve atraso.
7. Se o atraso for de até 15 minutos, não deve gerar falta.
8. Se o atraso passar de 15 minutos, deve marcar como falta.
9. Sistema permite selecionar quais aulas foram impactadas.
10. Sistema cria registro em `authorization_lessons`.
11. Sistema cria registro em `student_movements`.
12. Sistema dispara notificações simuladas.
13. Autorização é concluída.

---

## 11. Regras de Negócio

### RN01 — Tipos de autorização

A autorização pode ser:

- `entrada`
- `saida`

---

### RN02 — Status da autorização

A autorização deve possuir os seguintes status:

| Status | Descrição |
|---|---|
| `rascunho` | Autorização criada, mas ainda não enviada |
| `aguardando_professor` | Esperando validação do professor |
| `aprovada_professor` | Professor aprovou |
| `recusada_professor` | Professor recusou |
| `aguardando_portaria` | Esperando validação final da portaria |
| `validada_portaria` | Portaria confirmou entrada/saída |
| `concluida` | Processo finalizado |
| `cancelada` | Autorização cancelada |

---

### RN03 — Horários das aulas

O sistema deve considerar inicialmente 5 aulas por dia.

Cada aula tem 45 minutos.

As aulas começam às 13:00.

| Aula | Início | Fim |
|---|---|---|
| 1 | 13:00 | 13:45 |
| 2 | 13:45 | 14:30 |
| 3 | 14:30 | 15:15 |
| 4 | 15:15 | 16:00 |
| 5 | 16:00 | 16:45 |

---

### RN04 — Regra de atraso

- Até 15 minutos de atraso: `sem falta`.
- Mais de 15 minutos de atraso: `com falta`.
- O sistema deve permitir seleção manual das aulas faltadas.
- O campo de falta pode ser calculado automaticamente, mas deve ser editável por usuários autorizados.

---

### RN05 — Validação obrigatória

Para uma autorização de saída ser concluída, deve existir:

- Aluno selecionado.
- Turma vinculada.
- Curso vinculado.
- Professor vinculado.
- Aprovação do professor.
- Validação da portaria.
- Horário real de saída.
- Registro em `student_movements`.

---

### RN06 — Notificações

O sistema deve disparar notificações somente quando a portaria validar a entrada ou saída.

No MVP:

- O e-mail será enviado via Laravel Mail/Notification para Mailpit.
- O WhatsApp será apenas simulado com `Log::info`.
- Toda notificação deve ser registrada em `notification_logs`.

---

## 12. Banco de Dados

Criar migrations para as tabelas abaixo.

---

## 12.1 Tabela `users`

Usuários que acessam o painel.

Campos:

```text
id
name
email
password
role enum: admin, aqv, professor, portaria, coordenacao
is_active boolean default true
email_verified_at nullable timestamp
remember_token
created_at
updated_at
```

---

## 12.2 Tabela `students`

Cadastro de alunos.

Campos:

```text
id
name string
email string nullable
cpf string nullable unique
cep string nullable
phone string nullable
rm string unique
school_class_id foreignId nullable constrained school_classes
is_active boolean default true
created_at
updated_at
```

Relacionamentos:

```text
Student belongsTo SchoolClass
Student belongsToMany Guardian
Student hasMany Authorization
Student hasMany StudentMovement
```

---

## 12.3 Tabela `teachers`

Cadastro de professores.

Campos:

```text
id
user_id foreignId nullable constrained users
name string
email string nullable
cpf string nullable unique
cep string nullable
phone string nullable
rm string nullable unique
is_active boolean default true
created_at
updated_at
```

Relacionamentos:

```text
Teacher belongsTo User
Teacher belongsToMany SchoolClass
Teacher hasMany Authorization
```

---

## 12.4 Tabela `guardians`

Cadastro dos responsáveis dos alunos.

Campos:

```text
id
name string
email string nullable
phone string nullable
cpf string nullable unique
relationship string nullable
created_at
updated_at
```

Relacionamentos:

```text
Guardian belongsToMany Student
Guardian hasMany NotificationLog
```

---

## 12.5 Tabela `guardian_student`

Tabela pivô entre alunos e responsáveis.

Campos:

```text
id
student_id foreignId constrained students cascadeOnDelete
guardian_id foreignId constrained guardians cascadeOnDelete
is_primary boolean default false
created_at
updated_at
```

---

## 12.6 Tabela `courses`

Cursos.

Campos:

```text
id
name string
code string nullable unique
description text nullable
is_active boolean default true
created_at
updated_at
```

Relacionamentos:

```text
Course hasMany SchoolClass
Course hasManyThrough Student
```

---

## 12.7 Tabela `school_classes`

Turmas.

Campos:

```text
id
course_id foreignId constrained courses
name string
shift enum: manha, tarde, noite
year integer
is_active boolean default true
created_at
updated_at
```

Relacionamentos:

```text
SchoolClass belongsTo Course
SchoolClass hasMany Student
SchoolClass belongsToMany Teacher
SchoolClass hasMany LessonSchedule
```

---

## 12.8 Tabela `school_class_teacher`

Tabela pivô entre turmas e professores.

Campos:

```text
id
school_class_id foreignId constrained school_classes cascadeOnDelete
teacher_id foreignId constrained teachers cascadeOnDelete
created_at
updated_at
```

---

## 12.9 Tabela `lesson_schedules`

Horários das aulas.

Campos:

```text
id
school_class_id foreignId nullable constrained school_classes nullOnDelete
lesson_number integer
start_time time
end_time time
duration_minutes integer default 45
created_at
updated_at
```

Seed inicial:

```text
1 | 13:00 | 13:45 | 45
2 | 13:45 | 14:30 | 45
3 | 14:30 | 15:15 | 45
4 | 15:15 | 16:00 | 45
5 | 16:00 | 16:45 | 45
```

---

## 12.10 Tabela `authorizations`

Tabela principal das autorizações.

Campos:

```text
id
student_id foreignId constrained students
school_class_id foreignId constrained school_classes
course_id foreignId constrained courses
teacher_id foreignId constrained teachers
created_by foreignId constrained users
type enum: entrada, saida
status enum: rascunho, aguardando_professor, aprovada_professor, recusada_professor, aguardando_portaria, validada_portaria, concluida, cancelada
authorization_date date
scheduled_time time
real_time datetime nullable
reason text nullable
has_absence boolean default false
absence_count integer default 0
teacher_validated_at datetime nullable
teacher_validated_by foreignId nullable constrained users
gate_validated_at datetime nullable
gate_validated_by foreignId nullable constrained users
canceled_at datetime nullable
cancellation_reason text nullable
created_at
updated_at
```

Relacionamentos:

```text
Authorization belongsTo Student
Authorization belongsTo SchoolClass
Authorization belongsTo Course
Authorization belongsTo Teacher
Authorization belongsTo User as createdBy
Authorization hasMany AuthorizationLesson
Authorization hasOne StudentMovement
Authorization hasMany NotificationLog
```

---

## 12.11 Tabela `authorization_lessons`

Registra as aulas impactadas pela autorização.

Campos:

```text
id
authorization_id foreignId constrained authorizations cascadeOnDelete
lesson_number integer
start_time time
end_time time
status enum: presente, atraso_sem_falta, falta_justificada, falta_nao_justificada
created_at
updated_at
```

---

## 12.12 Tabela `student_movements`

Registra a movimentação real do aluno na portaria.

Campos:

```text
id
authorization_id foreignId constrained authorizations cascadeOnDelete
student_id foreignId constrained students
type enum: entrada, saida
occurred_at datetime
validated_by foreignId constrained users
notes text nullable
created_at
updated_at
```

Relacionamentos:

```text
StudentMovement belongsTo Authorization
StudentMovement belongsTo Student
StudentMovement belongsTo User as validator
```

---

## 12.13 Tabela `notification_logs`

Histórico de notificações.

Campos:

```text
id
authorization_id foreignId constrained authorizations cascadeOnDelete
student_id foreignId constrained students
guardian_id foreignId nullable constrained guardians nullOnDelete
channel enum: email, whatsapp_simulado, log
recipient string nullable
message text
status enum: enviado, erro, simulado
sent_at datetime nullable
error_message text nullable
created_at
updated_at
```

---

## 12.14 Tabela `audit_logs`

Histórico de ações importantes.

Campos:

```text
id
user_id foreignId nullable constrained users nullOnDelete
action string
model_type string nullable
model_id unsignedBigInteger nullable
old_values json nullable
new_values json nullable
ip_address string nullable
created_at
updated_at
```

---

## 13. Models Necessários

Criar os seguintes Models:

```text
User
Student
Teacher
Guardian
Course
SchoolClass
LessonSchedule
Authorization
AuthorizationLesson
StudentMovement
NotificationLog
AuditLog
```

---

## 14. Filament Resources Necessários

Criar os seguintes Resources no Filament:

```bash
php artisan make:filament-resource Student
php artisan make:filament-resource Teacher
php artisan make:filament-resource Guardian
php artisan make:filament-resource Course
php artisan make:filament-resource SchoolClass
php artisan make:filament-resource LessonSchedule
php artisan make:filament-resource Authorization
php artisan make:filament-resource StudentMovement
php artisan make:filament-resource NotificationLog
```

---

## 15. Resource: StudentResource

Campos no formulário:

- Nome
- E-mail
- CPF
- CEP
- Telefone
- RM
- Turma
- Ativo/Inativo

Tabela:

- Nome
- RM
- E-mail
- Telefone
- Turma
- Curso
- Status

Filtros:

- Turma
- Curso
- Ativo/Inativo

---

## 16. Resource: TeacherResource

Campos no formulário:

- Nome
- E-mail
- CPF
- CEP
- Telefone
- RM
- Usuário vinculado
- Turmas
- Ativo/Inativo

Tabela:

- Nome
- E-mail
- Telefone
- RM
- Status

---

## 17. Resource: GuardianResource

Campos no formulário:

- Nome
- E-mail
- Telefone
- CPF
- Parentesco
- Alunos vinculados
- Responsável principal

Tabela:

- Nome
- E-mail
- Telefone
- CPF
- Parentesco

---

## 18. Resource: CourseResource

Campos no formulário:

- Nome
- Código
- Descrição
- Ativo/Inativo

Tabela:

- Nome
- Código
- Status

---

## 19. Resource: SchoolClassResource

Campos no formulário:

- Nome da turma
- Curso
- Turno
- Ano
- Professores vinculados
- Ativo/Inativo

Tabela:

- Nome
- Curso
- Turno
- Ano
- Total de alunos
- Total de professores

---

## 20. Resource: AuthorizationResource

Este é o recurso principal do sistema.

### Campos do formulário

- Aluno
- Turma
- Curso
- Professor
- Tipo da autorização
- Data
- Horário previsto
- Motivo
- Aulas impactadas
- Com falta?
- Número de faltas
- Observações
- Status

### Comportamento esperado

Ao selecionar o aluno:

- Preencher turma automaticamente.
- Preencher curso automaticamente.

Ao selecionar tipo `entrada`:

- Permitir cálculo de atraso.
- Permitir marcar aulas perdidas.

Ao selecionar tipo `saida`:

- Permitir selecionar horário previsto de saída.
- Permitir validação posterior pela portaria.

### Tabela

Colunas:

- ID
- Aluno
- Tipo
- Turma
- Curso
- Professor
- Data
- Horário previsto
- Horário real
- Status
- Faltas
- Criado por
- Atualizado em

### Filtros

- Data
- Tipo
- Status
- Turma
- Curso
- Professor
- Com falta

### Ações

- Visualizar
- Editar
- Aprovar como professor
- Recusar como professor
- Validar na portaria
- Cancelar autorização
- Reenviar notificação simulada
- Ver logs

---

## 21. Ação: Aprovar como Professor

Criar action no `AuthorizationResource`.

Disponível apenas quando:

```text
status = aguardando_professor
usuário logado tem perfil professor ou admin
```

Ao executar:

```text
status = aprovada_professor
teacher_validated_at = now()
teacher_validated_by = auth()->id()
```

Depois:

```text
status = aguardando_portaria
```

---

## 22. Ação: Recusar como Professor

Disponível apenas quando:

```text
status = aguardando_professor
```

Ao executar:

```text
status = recusada_professor
teacher_validated_at = now()
teacher_validated_by = auth()->id()
```

Deve permitir informar motivo da recusa.

---

## 23. Ação: Validar na Portaria

Disponível apenas quando:

```text
status = aguardando_portaria
usuário logado tem perfil portaria ou admin
```

Ao executar:

1. Atualizar autorização:

```text
status = validada_portaria
real_time = now()
gate_validated_at = now()
gate_validated_by = auth()->id()
```

2. Criar registro em `student_movements`:

```text
authorization_id
student_id
type
occurred_at = now()
validated_by = auth()->id()
```

3. Calcular faltas, se for entrada com atraso.

4. Criar registros em `authorization_lessons`, se necessário.

5. Disparar evento:

```php
StudentMovementValidated
```

6. Após concluir, mudar status para:

```text
concluida
```

---

## 24. Cálculo de Atraso

Criar serviço recomendado:

```text
app/Services/AttendanceDelayService.php
```

Responsabilidade:

- Receber horário previsto.
- Receber horário real.
- Comparar diferença em minutos.
- Retornar se possui falta ou não.
- Retornar aulas impactadas.

Regra:

```text
diferença <= 15 minutos = sem falta
diferença > 15 minutos = com falta
```

---

## 25. Eventos e Listeners

Criar evento:

```bash
php artisan make:event StudentMovementValidated
```

Criar listeners:

```bash
php artisan make:listener SendGuardianEmailNotification
php artisan make:listener LogSimulatedWhatsappNotification
php artisan make:listener CreateNotificationLog
```

---

## 26. Evento `StudentMovementValidated`

O evento deve receber:

```php
public Authorization $authorization;
public StudentMovement $movement;
```

Esse evento deve ser disparado após a portaria validar a entrada ou saída.

---

## 27. Listener: SendGuardianEmailNotification

Responsabilidade:

- Buscar responsáveis vinculados ao aluno.
- Enviar e-mail para o responsável principal.
- Usar Laravel Notification ou Mail.
- Em ambiente local, os e-mails devem ser capturados pelo Mailpit.

---

## 28. Listener: LogSimulatedWhatsappNotification

Responsabilidade:

Simular envio de WhatsApp usando:

```php
Log::info('WhatsApp simulado enviado', [
    'student' => $student->name,
    'guardian' => $guardian->name,
    'phone' => $guardian->phone,
    'type' => $authorization->type,
    'occurred_at' => $movement->occurred_at,
]);
```

---

## 29. Listener: CreateNotificationLog

Responsabilidade:

Criar registros na tabela `notification_logs` para:

- E-mail enviado.
- WhatsApp simulado.

---

## 30. Mensagem de Notificação

Modelo de mensagem:

```text
[SAFE] O aluno {nome_do_aluno} teve sua {entrada/saída} registrada em {data_hora}. Turma: {turma}. Motivo: {motivo}.
```

Exemplo:

```text
[SAFE] O aluno João da Silva teve sua saída registrada em 19/05/2026 às 15:20. Turma: 3DEV. Motivo: consulta médica.
```

---

## 31. Configuração de E-mail Local

Configurar `.env` para Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="safe@escola.test"
MAIL_FROM_NAME="SAFE"
```

---

## 32. Políticas de Acesso

Criar Policies ou Gates para proteger ações.

### Admin

Pode fazer tudo.

### AQV

Pode:

- Criar autorizações.
- Editar autorizações ainda não finalizadas.
- Consultar histórico.

Não pode:

- Validar como professor.
- Validar como portaria, exceto se também tiver permissão.

### Professor

Pode:

- Ver autorizações ligadas a ele.
- Aprovar autorizações.
- Recusar autorizações.

Não pode:

- Validar portaria.
- Gerenciar alunos, cursos ou turmas.

### Portaria

Pode:

- Ver autorizações aguardando portaria.
- Validar entrada.
- Validar saída.
- Consultar movimentações do dia.

Não pode:

- Aprovar como professor.
- Editar dados acadêmicos.

### Coordenação

Pode:

- Consultar dashboard.
- Consultar relatórios.
- Consultar histórico.

---

## 33. Seeders

Criar seeders para:

- Usuário admin padrão.
- Usuário AQV.
- Usuário professor.
- Usuário portaria.
- Curso exemplo.
- Turma exemplo.
- Professor exemplo.
- Alunos exemplo.
- Responsáveis exemplo.
- Horários padrão das aulas.

Usuário admin sugerido:

```text
name: Administrador
email: admin@safe.test
password: password
role: admin
```

---

## 34. Dados de Teste Recomendados

Criar pelo menos:

- 1 curso: Desenvolvimento de Sistemas
- 1 turma: 3DEV
- 2 professores
- 5 alunos
- 5 responsáveis
- 5 horários de aula
- 3 autorizações de entrada
- 3 autorizações de saída

---

## 35. Validações

Aplicar validações nos formulários:

### Aluno

- Nome obrigatório.
- RM obrigatório e único.
- CPF único, se informado.
- E-mail válido, se informado.
- Telefone opcional.

### Professor

- Nome obrigatório.
- E-mail válido.
- CPF único, se informado.
- RM único, se informado.

### Responsável

- Nome obrigatório.
- E-mail válido, se informado.
- Telefone obrigatório para simulação de WhatsApp.

### Autorização

- Aluno obrigatório.
- Professor obrigatório.
- Tipo obrigatório.
- Data obrigatória.
- Horário previsto obrigatório.
- Motivo recomendado.
- Status obrigatório.

---

## 36. Requisitos Funcionais

| Código | Requisito |
|---|---|
| RF01 | O sistema deve permitir login de usuários autorizados |
| RF02 | O administrador deve cadastrar alunos |
| RF03 | O administrador deve cadastrar professores |
| RF04 | O administrador deve cadastrar responsáveis |
| RF05 | O administrador deve cadastrar cursos |
| RF06 | O administrador deve cadastrar turmas |
| RF07 | O administrador deve vincular alunos a turmas |
| RF08 | O administrador deve vincular professores a turmas |
| RF09 | O sistema deve permitir abrir autorização de entrada |
| RF10 | O sistema deve permitir abrir autorização de saída |
| RF11 | O sistema deve permitir selecionar aulas faltadas |
| RF12 | O sistema deve calcular falta após 15 minutos de atraso |
| RF13 | O professor deve aprovar autorização |
| RF14 | O professor deve recusar autorização |
| RF15 | A portaria deve validar entrada |
| RF16 | A portaria deve validar saída |
| RF17 | O sistema deve registrar horário real da movimentação |
| RF18 | O sistema deve simular WhatsApp via Log::info |
| RF19 | O sistema deve enviar e-mail de teste via Mailpit |
| RF20 | O sistema deve registrar logs de notificação |
| RF21 | O dashboard deve mostrar indicadores do dia |
| RF22 | O sistema deve permitir consultar histórico por aluno |
| RF23 | O sistema deve registrar logs de auditoria |

---

## 37. Requisitos Não Funcionais

| Código | Requisito |
|---|---|
| RNF01 | O sistema deve ser desenvolvido em Laravel |
| RNF02 | O painel deve ser construído com Filament |
| RNF03 | O banco deve usar migrations |
| RNF04 | O sistema deve usar autenticação |
| RNF05 | O sistema deve ter controle de permissões por perfil |
| RNF06 | O sistema deve registrar histórico de ações importantes |
| RNF07 | O painel deve ser responsivo |
| RNF08 | O sistema deve validar dados obrigatórios |
| RNF09 | O sistema deve evitar exclusão acidental de registros importantes |
| RNF10 | O código deve ser organizado e preparado para expansão |

---

## 38. Critérios de Aceite

O projeto será considerado funcional quando:

- O usuário conseguir fazer login no painel.
- O admin conseguir cadastrar alunos, professores, responsáveis, cursos e turmas.
- Um aluno puder ser vinculado a uma turma.
- Uma turma puder ser vinculada a um curso.
- Professores puderem ser vinculados a turmas.
- Uma autorização de entrada puder ser criada.
- Uma autorização de saída puder ser criada.
- O professor conseguir aprovar ou recusar uma autorização.
- A portaria conseguir validar uma autorização aprovada.
- O sistema registrar o horário real da entrada ou saída.
- O sistema criar uma movimentação em `student_movements`.
- O sistema calcular atraso maior que 15 minutos como falta.
- O sistema permitir registrar aulas impactadas.
- O sistema enviar e-mail para Mailpit.
- O sistema registrar WhatsApp simulado no log.
- O sistema criar registros em `notification_logs`.
- O dashboard exibir indicadores atualizados.
- O histórico da autorização ficar disponível para consulta.

---

## 39. Estrutura Recomendada de Pastas

```text
app/
├── Models/
│   ├── Student.php
│   ├── Teacher.php
│   ├── Guardian.php
│   ├── Course.php
│   ├── SchoolClass.php
│   ├── LessonSchedule.php
│   ├── Authorization.php
│   ├── AuthorizationLesson.php
│   ├── StudentMovement.php
│   ├── NotificationLog.php
│   └── AuditLog.php
│
├── Filament/
│   ├── Resources/
│   │   ├── StudentResource/
│   │   ├── TeacherResource/
│   │   ├── GuardianResource/
│   │   ├── CourseResource/
│   │   ├── SchoolClassResource/
│   │   ├── LessonScheduleResource/
│   │   ├── AuthorizationResource/
│   │   ├── StudentMovementResource/
│   │   └── NotificationLogResource/
│   │
│   ├── Pages/
│   │   └── Dashboard.php
│   │
│   └── Widgets/
│       ├── AuthorizationsTodayWidget.php
│       ├── PendingTeacherWidget.php
│       ├── PendingGateWidget.php
│       ├── MovementsTodayWidget.php
│       └── LatestNotificationsWidget.php
│
├── Events/
│   └── StudentMovementValidated.php
│
├── Listeners/
│   ├── SendGuardianEmailNotification.php
│   ├── LogSimulatedWhatsappNotification.php
│   └── CreateNotificationLog.php
│
├── Notifications/
│   └── StudentMovementNotification.php
│
├── Services/
│   └── AttendanceDelayService.php
│
└── Policies/
    └── AuthorizationPolicy.php
```

---

## 40. Ordem Recomendada de Implementação

A IA construtora deve seguir esta ordem:

1. Criar projeto Laravel.
2. Instalar e configurar Filament.
3. Configurar banco de dados.
4. Criar migrations.
5. Criar models e relacionamentos.
6. Criar seeders.
7. Criar autenticação e perfis.
8. Criar Resources básicos: alunos, professores, responsáveis, cursos e turmas.
9. Criar Resource de horários de aula.
10. Criar Resource de autorizações.
11. Implementar ações de professor.
12. Implementar ação de validação da portaria.
13. Criar serviço de cálculo de atraso/falta.
14. Criar evento `StudentMovementValidated`.
15. Criar listeners de e-mail, WhatsApp simulado e log.
16. Configurar Mailpit.
17. Criar dashboard com widgets.
18. Criar filtros e buscas.
19. Criar regras de permissão.
20. Testar fluxo completo de entrada e saída.

---

## 41. Melhorias Futuras

Não implementar no MVP, mas deixar estrutura preparada para:

- Integração real com WhatsApp Business API.
- QR Code para validação rápida na portaria.
- Assinatura digital do responsável.
- Painel externo para responsáveis.
- Aplicativo mobile.
- Exportação para PDF.
- Exportação para Excel.
- Integração com catraca escolar.
- Leitor de carteirinha ou RM.
- Notificações em tempo real.
- Controle completo de presença por aula.
- Módulo de ocorrências disciplinares.

---

## 42. Instrução Final para a IA Construtora

Construa o sistema SAFE como um MVP funcional em Laravel com Filament.

Priorize:

1. Código organizado.
2. Migrations completas.
3. Relacionamentos Eloquent corretos.
4. Resources funcionais no Filament.
5. Fluxo completo de autorização.
6. Validação por professor.
7. Validação por portaria.
8. Registro de movimentação real.
9. Simulação de notificações.
10. Dashboard operacional.

Não implemente integrações externas reais neste MVP.

Use Mailpit para e-mails e `Log::info` para WhatsApp simulado.

O sistema deve estar pronto para demonstração escolar e para documentação técnica/operacional.
