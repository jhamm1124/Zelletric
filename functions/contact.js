export async function onRequest(context) {
  const { request } = context;
  
  if (request.method !== "POST") {
    return new Response(JSON.stringify({ success: false, message: "Method not allowed" }), {
      status: 405,
      headers: { "Content-Type": "application/json" }
    });
  }

  try {
    const data = await request.json();
    
    // Basic validation
    if (!data.name || !data.email || !data.message) {
      return new Response(JSON.stringify({ success: false, message: "Missing required fields" }), {
        status: 400,
        headers: { "Content-Type": "application/json" }
      });
    }

    // Prepare email content for Mailchannels
    const htmlEmail = `
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
            .header { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
            .details { margin: 20px 0; }
            .detail-row { margin-bottom: 10px; }
            .label { font-weight: bold; color: #2c3e50; }
            .message {
                background-color: #f8f9fa;
                padding: 15px;
                border-left: 4px solid #3498db;
                margin: 20px 0;
            }
            .footer {
                margin-top: 30px;
                font-size: 0.9em;
                color: #7f8c8d;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
            <p>${new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })} at ${new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</p>
        </div>

        <div class='details'>
            <div class='detail-row'><span class='label'>Name:</span> ${data.name}</div>
            <div class='detail-row'><span class='label'>Email:</span> <a href='mailto:${data.email}'>${data.email}</a></div>
            <div class='detail-row'><span class='label'>Phone:</span> ${data.phone || 'Not provided'}</div>
            ${data.service ? `<div class='detail-row'><span class='label'>Service:</span> ${data.service}</div>` : ""}
        </div>

        <div class='message'>
            <div class='label'>Message:</div>
            <div style="white-space: pre-wrap;">${data.message}</div>
        </div>

        <div class='footer'>
            <p>This message was sent from the contact form on zellectric.com</p>
        </div>
    </body>
    </html>
    `;

    const emailBody = {
      personalizations: [
        {
          to: [{ email: "info@zellectric.com", name: "Zellectric Admin" }],
        },
      ],
      from: { email: "no-reply@zellectric.com", name: "Zellectric Contact Form" },
      subject: `New Contact Form Submission from ${data.name}`,
      content: [
        {
          type: "text/html",
          value: htmlEmail,
        },
      ],
    };

    const sendEmail = await fetch("https://api.mailchannels.net/tx/v1/send", {
      method: "POST",
      headers: { "content-type": "application/json" },
      body: JSON.stringify(emailBody),
    });

    if (sendEmail.ok) {
      return new Response(JSON.stringify({ success: true, message: "Email sent successfully" }), {
        status: 200,
        headers: { "Content-Type": "application/json" }
      });
    } else {
      const respText = await sendEmail.text();
      return new Response(JSON.stringify({ success: false, message: "Failed to send email", debug: respText }), {
        status: 500,
        headers: { "Content-Type": "application/json" }
      });
    }
  } catch (error) {
    return new Response(JSON.stringify({ success: false, message: error.message }), {
      status: 500,
      headers: { "Content-Type": "application/json" }
    });
  }
}
