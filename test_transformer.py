from transformers import pipeline

# Configura un generador de texto con GPT-2 o un modelo más grande
generator = pipeline("text-generation", model="gpt2")

# Función para generar preguntas de encuesta con respuestas


def generar_encuesta(tema, num_preguntas=10):
    encuesta = []
    for i in range(num_preguntas):
        prompt = f"Escribe una pregunta para una encuesta sobre {tema}:"
        pregunta = generator(prompt, max_length=50, num_return_sequences=1)[
            0]['generated_text']

        # Genera opciones de respuesta simuladas (puedes mejorar esto)
        opciones = [f"Opción {j}" for j in range(1, 6)]
        # Se selecciona la primera como correcta por defecto
        respuesta_correcta = opciones[0]

        encuesta.append({
            "pregunta": pregunta.strip(),
            "opciones": opciones,
            "respuesta_correcta": respuesta_correcta
        })
    return encuesta


# Ejemplo de uso
tema = "Inteligencia Artificial"
encuesta_generada = generar_encuesta(tema)

# Muestra la encuesta generada
for idx, item in enumerate(encuesta_generada, 1):
    print(f"Pregunta {idx}: {item['pregunta']}")
    print("Opciones:")
    for opcion in item['opciones']:
        print(f"  - {opcion}")
    print(f"Respuesta correcta: {item['respuesta_correcta']}\n")
