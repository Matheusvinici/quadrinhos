require('dotenv').config();
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const axios = require('axios');

const APP_URL = process.env.APP_URL || 'http://localhost:8000';
const PORT = process.env.PORT || 3000;
const BOT_TOKEN = process.env.BOT_TOKEN || '';

const app = express();
app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: { headless: true, args: ['--no-sandbox'] }
});

// State machine for conversations
const sessions = {};

const STEPS = {
    INIT: 'init',
    BARBER: 'barber',
    DAY: 'day',
    TIME: 'time',
    SERVICE: 'service',
    CONFIRM: 'confirm',
    DONE: 'done'
};

client.on('qr', (qr) => {
    qrcode.generate(qr, { small: true });
    console.log('Scan the QR code above with WhatsApp');
});

client.on('ready', () => {
    console.log('WhatsApp Bot is ready!');
});

client.on('message', async (msg) => {
    if (msg.from.includes('@g.us')) return; // Ignore group messages
    if (msg.body.startsWith('/')) return; // Commands

    const from = msg.from;
    const text = msg.body.trim().toLowerCase();

    if (!sessions[from]) {
        sessions[from] = { step: STEPS.INIT };
    }

    const session = sessions[from];

    switch (session.step) {
        case STEPS.INIT:
            await handleInit(from, msg, session);
            break;
        case STEPS.BARBER:
            await handleBarber(from, msg, session, text);
            break;
        case STEPS.DAY:
            await handleDay(from, msg, session, text);
            break;
        case STEPS.TIME:
            await handleTime(from, msg, session, text);
            break;
        case STEPS.SERVICE:
            await handleService(from, msg, session, text);
            break;
        case STEPS.CONFIRM:
            await handleConfirm(from, msg, session, text);
            break;
        default:
            session.step = STEPS.INIT;
            await sendMsg(from, 'Olá! Digite *"agendar"* para marcar um horário ou *"sair"* para encerrar.');
            break;
    }
});

async function handleInit(from, msg, session) {
    const text = msg.body.trim().toLowerCase();

    if (text === 'agendar' || text === 'quero agendar' || text === 'agendamento') {
        try {
            const res = await axios.get(`${APP_URL}/api/bot/barbeiros`);
            const barbeiros = res.data;

            if (barbeiros.length === 0) {
                await sendMsg(from, 'Desculpe, não temos barbeiros disponíveis no momento.');
                session.step = STEPS.INIT;
                return;
            }

            let lista = '👨‍🦰 *Barbeiros disponíveis:*\n\n';
            barbeiros.forEach((b, i) => {
                lista += `${i + 1} - ${b.nome}\n`;
            });
            lista += '\nDigite o *número* ou *nome* do barbeiro desejado.';

            session.barbers = barbeiros;
            session.step = STEPS.BARBER;
            await sendMsg(from, lista);
        } catch (err) {
            console.error('Error fetching barbers:', err.message);
            await sendMsg(from, 'Desculpe, ocorreu um erro. Tente novamente mais tarde.');
            session.step = STEPS.INIT;
        }
    } else if (text === 'sair' || text === 'cancelar') {
        delete sessions[from];
        await sendMsg(from, '✅ Atendimento encerrado. Digite *"agendar"* quando quiser marcar um horário.');
    } else {
        await sendMsg(from, '👋 Olá! Bem-vindo à barbearia!\n\nDigite *"agendar"* para marcar um horário.');
    }
}

async function handleBarber(from, msg, session, text) {
    const barbers = session.barbers || [];
    let selected = null;

    // Try to find by number
    const num = parseInt(text);
    if (num > 0 && num <= barbers.length) {
        selected = barbers[num - 1];
    }

    // Try to find by name
    if (!selected) {
        selected = barbers.find(b => b.nome.toLowerCase().includes(text));
    }

    if (!selected) {
        await sendMsg(from, '❌ Barbeiro não encontrado. Digite o número ou nome correto:');
        return;
    }

    session.barber = selected;
    session.step = STEPS.DAY;

    try {
        const res = await axios.get(`${APP_URL}/api/bot/dias-disponiveis`, {
            params: { barbeiro_id: selected.id }
        });
        const dias = res.data;

        if (dias.length === 0) {
            await sendMsg(from, '😕 Infelizmente não há dias disponíveis para este barbeiro no momento.');
            session.step = STEPS.INIT;
            return;
        }

        let lista = `📅 *${selected.nome}* - Dias disponíveis:\n\n`;
        dias.forEach((d, i) => {
            lista += `${i + 1} - ${d.label}\n`;
        });
        lista += '\nDigite o *número* do dia desejado.';

        session.dias = dias;
        await sendMsg(from, lista);
    } catch (err) {
        console.error('Error fetching days:', err.message);
        await sendMsg(from, 'Erro ao buscar dias disponíveis. Tente novamente.');
        session.step = STEPS.INIT;
    }
}

async function handleDay(from, msg, session, text) {
    const dias = session.dias || [];
    const num = parseInt(text);

    if (num < 1 || num > dias.length) {
        await sendMsg(from, '❌ Dia inválido. Digite o número correto:');
        return;
    }

    const dia = dias[num - 1];
    session.dia = dia;

    try {
        const res = await axios.get(`${APP_URL}/api/bot/horarios`, {
            params: {
                barbeiro_id: session.barber.id,
                data: dia.data
            }
        });
        const horarios = res.data;

        if (horarios.length === 0) {
            await sendMsg(from, '😕 Não há horários disponíveis para esta data.');
            session.step = STEPS.DAY;
            return;
        }

        let lista = `🕐 *Horários disponíveis para ${dia.label}:*\n\n`;
        horarios.forEach((h, i) => {
            lista += `${i + 1} - ${h}\n`;
        });
        lista += '\nDigite o *número* do horário desejado.';

        session.horarios = horarios;
        session.step = STEPS.TIME;
        await sendMsg(from, lista);
    } catch (err) {
        console.error('Error fetching times:', err.message);
        await sendMsg(from, 'Erro ao buscar horários. Tente novamente.');
        session.step = STEPS.DAY;
    }
}

async function handleTime(from, msg, session, text) {
    const horarios = session.horarios || [];
    const num = parseInt(text);

    if (num < 1 || num > horarios.length) {
        await sendMsg(from, '❌ Horário inválido. Digite o número correto:');
        return;
    }

    session.hora = horarios[num - 1];

    try {
        const res = await axios.get(`${APP_URL}/api/bot/servicos`);
        const servicos = res.data;

        if (servicos.length === 0) {
            await sendMsg(from, '😕 Não há serviços disponíveis no momento.');
            session.step = STEPS.INIT;
            return;
        }

        let lista = '💈 *Serviços disponíveis:*\n\n';
        servicos.forEach((s, i) => {
            lista += `${i + 1} - ${s.nome} - R$ ${parseFloat(s.preco).toFixed(2)}\n`;
        });
        lista += '\nDigite o *número* do serviço desejado.';

        session.servicos = servicos;
        session.step = STEPS.SERVICE;
        await sendMsg(from, lista);
    } catch (err) {
        console.error('Error fetching services:', err.message);
        await sendMsg(from, 'Erro ao buscar serviços. Tente novamente.');
        session.step = STEPS.DAY;
    }
}

async function handleService(from, msg, session, text) {
    const servicos = session.servicos || [];
    const num = parseInt(text);

    if (num < 1 || num > servicos.length) {
        await sendMsg(from, '❌ Serviço inválido. Digite o número correto:');
        return;
    }

    session.servico = servicos[num - 1];

    const confirmMsg = `✅ *Confirme seu agendamento:*

👨‍🦰 Barbeiro: ${session.barber.nome}
📅 Data: ${session.dia.label}
🕐 Horário: ${session.hora}
💈 Serviço: ${session.servico.nome}
💰 Valor: R$ ${parseFloat(session.servico.preco).toFixed(2)}

Digite *"confirmar"* para finalizar ou *"cancelar"* para desistir.`;

    session.step = STEPS.CONFIRM;
    await sendMsg(from, confirmMsg);
}

async function handleConfirm(from, msg, session, text) {
    if (text === 'confirmar') {
        try {
            const payload = {
                barbeiro_id: session.barber.id,
                servico_id: session.servico.id,
                data: session.dia.data,
                hora: session.hora,
                cliente_nome: 'Cliente WhatsApp',
                cliente_telefone: from.replace('@c.us', ''),
                whatsapp_id: from.replace('@c.us', ''),
            };

            const res = await axios.post(`${APP_URL}/api/bot/agendar`, payload);

            if (res.data.success) {
                await sendMsg(from, `🎉 *Agendamento confirmado!*

📅 Data: ${res.data.data}
🕐 Horário: ${res.data.hora}
👨‍🦰 Barbeiro: ${res.data.barbeiro}
💈 Serviço: ${res.data.servico}
💰 Valor: R$ ${parseFloat(res.data.preco).toFixed(2)}

🕐 *Lembrete:* Enviaremos uma mensagem 1 hora antes do horário!`);
                delete sessions[from];
            } else {
                await sendMsg(from, '❌ Erro ao confirmar agendamento. Tente novamente.');
                session.step = STEPS.INIT;
            }
        } catch (err) {
            console.error('Error creating appointment:', err.response?.data || err.message);
            await sendMsg(from, '❌ Erro ao confirmar agendamento. Tente novamente.');
            session.step = STEPS.INIT;
        }
    } else if (text === 'cancelar' || text === 'sair') {
        delete sessions[from];
        await sendMsg(from, '✅ Agendamento cancelado. Digite *"agendar"* quando quiser tentar novamente.');
    } else {
        await sendMsg(from, 'Digite *"confirmar"* para finalizar ou *"cancelar"* para desistir.');
    }
}

async function sendMsg(to, message) {
    try {
        await client.sendMessage(to, message);
    } catch (err) {
        console.error('Error sending message:', err.message);
    }
}

// Reminder system - check every minute for appointments 1 hour away
setInterval(async () => {
    try {
        const { data } = await axios.get(`${APP_URL}/api/bot/lembretes`);
        if (!data.length) return;
        for (const ag of data) {
            const numero = ag.cliente_telefone.replace(/\D/g, '');
            const chatId = `55${numero}@c.us`;
            const msg = `✂️ *Lembrete de Agendamento*\n\nOlá *${ag.cliente_nome}*! Lembramos que você tem um horário marcado hoje às *${ag.hora}* com *${ag.barbeiro_nome}*.\n\nServiço: ${ag.servicos}\n\nTe esperamos! 🫡`;
            await client.sendMessage(chatId, msg);
            console.log(`Reminder sent to ${ag.cliente_nome} (${ag.cliente_telefone})`);
        }
    } catch (err) {
        // silent - server may not be running
    }
}, 60000);

client.initialize();

app.get('/health', (req, res) => {
    res.json({ status: 'ok', ready: client.info?.wid?.user || false });
});

app.listen(PORT, () => {
    console.log(`Bot server running on port ${PORT}`);
});
