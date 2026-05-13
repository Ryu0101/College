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

# Download required NLTK data
nltk.download('stopwords', quiet=True)
nltk.download('wordnet', quiet=True)
nltk.download('omw-1.4', quiet=True)

# Initialize stopwords and lemmatizer
stop_words = set(stopwords.words("english"))
lemmatizer = WordNetLemmatizer()

# Load the trained model
@st.cache_resource
def load_model():
    return joblib.load(r'C:\Users\DELL\my_project\project1\emotion_classifier.pkl')

model = load_model()
class_names = model.classes_

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
st.write("Enter a sentence below to predict its emotion using a machine learning model!")

# Sidebar for settings
st.sidebar.header("Settings")
show_confidence = st.sidebar.checkbox("Show Prediction Confidence", value=False)
show_lime = st.sidebar.checkbox("Show LIME Explanation", value=False)
example_texts = st.sidebar.checkbox("Show Example Texts", value=False)

# Example texts in sidebar
if example_texts:
    st.sidebar.write("Try these examples:")
    st.sidebar.write("- 'I feel great today'")
    st.sidebar.write("- 'This is so sad'")
    st.sidebar.write("- 'I’m really angry right now'")

# Text input
user_input = st.text_area("Your Text", value="", placeholder="Type here...", height=150)

# Prediction button
if st.button("Predict"):
    if user_input:
        with st.spinner("Processing..."):
            # Preprocess input
            processed_input = normalize_text(user_input)
            
            # Make prediction
            prediction = model.predict([processed_input])[0]
            
            # Display result
            st.success(f"Predicted Emotion: **{prediction}**")
            
            # Show confidence scores
            if show_confidence:
                probs = model.predict_proba([processed_input])[0]
                prob_df = pd.DataFrame({
                    "Emotion": class_names,
                    "Probability": probs
                }).sort_values(by="Probability", ascending=False)
                st.write("Prediction Confidence:")
                st.dataframe(prob_df.style.format({"Probability": "{:.2%}"}))

            # Show LIME explanations
            if show_lime:
                st.subheader("LIME Explanation for All Emotions:")
                explainer = LimeTextExplainer(class_names=class_names)

                for label_idx, emotion in enumerate(class_names):
                    st.markdown(f"### {emotion.capitalize()} Explanation")
                    exp = explainer.explain_instance(
                        processed_input,
                        model.predict_proba,
                        num_features=10,
                        labels=[label_idx]
                    )
                    components.html(exp.as_html(), height=600, scrolling=True)

            # Show preprocessed text
            with st.expander("See Preprocessed Text"):
                st.write(processed_input)
    else:
        st.error("Please enter some text to classify!")

# Footer
st.markdown("---")
st.write("Built with ❤️ using Streamlit, scikit-learn, and LIME.")
st.write("Model trained on emotion-labeled text data.")
