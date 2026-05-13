
import streamlit as st
from transformers import pipeline

# Load the emotion classifier
classifier = pipeline("text-classification", model="bhadresh-savani/bert-base-uncased-emotion")

st.title("Emotion Detection from Text")
st.write("Enter a sentence to analyze the emotion.")

# User input
user_input = st.text_area("Enter text here:")

if st.button("Analyze Emotion"):
    if user_input.strip():
        result = classifier(user_input)
        label = result[0]['label']
        score = result[0]['score']
        st.success(f"Predicted Emotion: **{label}** (Confidence: {score:.2f})")
    else:
        st.warning("Please enter some text for analysis.")