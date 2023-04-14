// Importar biblioteca de Azure Cognitive Services
const { TextAnalyticsClient, AzureKeyCredential } = require("@azure/ai-text-analytics");
// Configurar cliente de Text Analytics
const key = "41cdaf748d334024b49bb337a1f75436";
const endpoint = "https://text-analitycs.cognitiveservices.azure.com/";
const client = new TextAnalyticsClient(endpoint, new AzureKeyCredential(key));

// Función para detectar sentimientos en el texto
async function detectarSentimientos(texto) {
  return new Promise((resolve, reject) => {
    client.analyzeSentiment(texto).then(response => {
      // console.log(response[0].sentiment)
      resolve(response[0].sentiment)
    }).catch(error => reject(error))
  })
}

// Función para recomendar películas basadas en el estado de ánimo
async function recomendarPeliculas(emocion) {
  return new Promise(async (resolve, reject) => {
    const peliculasRecomendadas = [];
    const moviesList = await getMovies();
    console.log(moviesList, 'moviesList')
    for (let i = 0; i < moviesList.length; i++) {
      const element = moviesList[i];
      console.log(element, 'element')
      const description = moviesList[i].overview
      console.log(moviesList[i]);
      const movieSentiment = await detectarSentimientos([description])
      if (movieSentiment == emocion) {
        peliculasRecomendadas.push(moviesList[i].original_title)
      }
    }
    console.log('peliculasRecomendadas', peliculasRecomendadas)
    resolve(peliculasRecomendadas)
  })
}

async function getMovies() {
  return new Promise((resolve, reject) => {
    // console.log('moviesList ARRAY', moviesList)
    const MovieDB = require('moviedb')('2361b6c133856991064a91d1b1304ad1');
    MovieDB.searchMovie({ query: 'matar, asesino' }, async (err, movies) => {
      // console.log('movies', movies);
      const results = movies.results
      // console.log(results, 'RESULTADOS');
      resolve(results)
    });
  })
}

const btnSendText = document.querySelector('#send-text')
btnSendText.addEventListener('click', async (e) => {
  var text = document.querySelector('#text')?.value
  if (text) {
    var array = [text]
    const sentiment = await detectarSentimientos(array)
    // console.log('sentiment', sentiment, array)
    if (sentiment) {
      const recommendMovies = await recomendarPeliculas(sentiment)
      console.log('recommendMovies', recommendMovies)
      // console.log('recommendMovies', recommendMovies, sentiment, emocionList, peliculas)
      const sentimentType = document.querySelector('#sentiment-type')
      sentimentType.innerHTML = sentiment.toUpperCase()
      const recommendMoviesList = document.querySelector('#recommend-movies')
      recommendMoviesList.innerHTML = ''
      recommendMovies.forEach(movie => {
        recommendMoviesList.insertAdjacentHTML('beforeend', '<li>' + movie + '</li>')
      });
    }
  }
})

const occasions = {
  "family": "en familia",
  "girlfriend": "con mi novia"
}

const btnSendOccasion = document.querySelectorAll('input[name="occasion"]')
btnSendOccasion.forEach((element) => {
  element.addEventListener('click', async (e) => {
    console.log('Generando respuesta...');
    const text = 'Recomiendame 5 peliculas para ver ' + occasions[e.srcElement.value] + ' en las plataformas de streaming con el nombre de la pelicula y la plataforma'
    console.log(text, e.srcElement.value);
    const movies = await testOpenai(text)
    console.log('movies OPENAI', movies);
    const recommendMoviesList = document.querySelector('#recommend-movies')
    recommendMoviesList.innerHTML = ''
    movies.forEach((movie) => {
      if (typeof movie == 'string' && movie != "") {
        recommendMoviesList.insertAdjacentHTML('beforeend', '<li>' + movie + '</li>')
      }
    })

  })
})

async function testOpenai(text) {
  console.log('Obteniendo respuesta...');
  const { Configuration, OpenAIApi }  = require('openai');
  const configuration = new Configuration({
      organization: "org-HU4v4GhKsGylwgFUxOayt1dS",
      apiKey: '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',
  });
  const openai = new OpenAIApi(configuration);
  const response = await openai.listEngines();
  const completion = await openai.createChatCompletion({
    model: "gpt-3.5-turbo",
    messages: [{role: "user", content: text}],
  });
  console.log(response, 'listEngines');
  console.log('Full response openai', completion.data, typeof completion.data.choices[0].message.content);
  const splitContent = setListMoviesResponse(completion.data.choices[0].message.content)
  console.log(splitContent, 'Listado peliculas')
  return splitContent
}

function setListMoviesResponse(text) {
  return text.split('\n')
}
