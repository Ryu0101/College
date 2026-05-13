import streamlit as st
import joblib
import pandas as pd
import re
import nltk
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer
from lime.lime_text import LimeTextExplainer
import matplotlib.pyplot as plt
import streamlit.components.v1 as components
from transformers import pipeline

# Download required NLTK data
nltk.download('stopwords', quiet=True)
nltk.download('wordnet', quiet=True)
nltk.download('omw-1.4', quiet=True)

# Initialize stopwords and lemmatizer
stop_words = set(stopwords.words("english"))
lemmatizer = WordNetLemmatizer()

# Load the trained ML model
@st.cache_resource
def load_ml_model():
    return joblib.load(r'C:\Users\DELL\my_project\project1\emotion_classifier.pkl')

ml_model = load_ml_model()
class_names = ml_model.classes_

# Load the BERT model
bert_classifier = pipeline("text-classification", model="SamLowe/roberta-base-go_emotions")

# Preprocessing functions
def lemmatization(text):
    return " ".join([lemmatizer.lemmatize(word) for word in text.split()])

def remove_stop_words(text):
    return " ".join([word for word in text.split() if word not in stop_words])

def Removing_numbers(text):
    return ''.join([char for char in text if not char.isdigit()])

def lower_case(text):
    return text.lower()

def Removing_punctuations(text):
    text = re.sub(r'[^\w\s]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def Removing_urls(text):
    return re.sub(r'https?://\S+|www\.\S+', '', text)

def normalize_text(text):
    text = lower_case(text)
    text = remove_stop_words(text)
    text = Removing_numbers(text)
    text = Removing_punctuations(text)
    text = Removing_urls(text)
    text = lemmatization(text)
    return text

# Streamlit App
st.title("Emotion Classifier")
st.write("Enter a sentence below to see predictions!")

# Text input
user_input = st.text_area("Your Text", value="", placeholder="Type here...", height=150)

if st.button("Predict"):
    if user_input:
        with st.spinner("Processing..."):
            processed_input = normalize_text(user_input)
            
            # ML Model Prediction
            ml_prediction = ml_model.predict([processed_input])[0]
            ml_probs = ml_model.predict_proba([processed_input])[0]
            ml_prob_df = pd.DataFrame({"Emotion": class_names, "Probability": ml_probs}).sort_values(by="Probability", ascending=False)
            
            # BERT Model Prediction
            bert_result = bert_classifier(user_input)
            bert_label = bert_result[0]['label']
            bert_score = bert_result[0]['score']

            # Display preprocessed text
            st.subheader("Preprocessed Text")
            st.write(processed_input)            
            
            # Display results in two columns
            col1, col2 = st.columns(2)
            
            with col1:
                st.subheader("ML Model Prediction")
                st.success(f"Predicted Emotion: **{ml_prediction}**")
                st.write("Prediction Confidence:")
                st.dataframe(ml_prob_df.style.format({"Probability": "{:.2%}"}))
                
            with col2:
                st.subheader("BERT Model Prediction")
                st.success(f"Predicted Emotion: **{bert_label}**")
                st.write(f"Confidence: {bert_score:.2%}")
    else:
        st.error("Please enter some text to classify!")

# Footer
st.markdown("---")
st.write("Built with ❤️ using Streamlit, scikit-learn, and Transformers.")