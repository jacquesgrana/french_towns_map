class WeatherLibrary {


  /**
   * Méthode qui renvoie la description du temps 'sensible' selon le code passé en paramètre.
   * @param {int} code : code du temps 'sensible' prévu.
   * @returns {string} : description correspondante.
   */
static getWeatherNameByCode = (code) => {
    let toReturn = "";
    switch (code) {
      case 0:
        toReturn = "Soleil";
        break;
      case 1:
        toReturn = "Peu nuageux";
        break;
      case 2:
        toReturn = "Ciel voilé";
        break;
      case 3:
        toReturn = "Nuageux";
        break;
      case 4:
        toReturn = "Très nuageux";
        break;
      case 5:
        toReturn = "Couvert";
        break;
      case 6:
        toReturn = "Brouillard";
        break;
      case 7:
        toReturn = "Brouillard givrant";
        break;
  
      case 10:
        toReturn = "Pluie faible";
        break;
      case 11:
        toReturn = "Pluie modérée";
        break;
      case 12:
        toReturn = "Pluie forte";
        break;
      case 13:
        toReturn = "Pluie faible verglaçante";
        break;
      case 14:
        toReturn = "Pluie modérée verglaçante";
        break;
      case 15:
        toReturn = "Pluie forte verglaçante";
        break;
      case 16:
        toReturn = "Bruine";
        break;
  
      case 20:
        toReturn = "Neige faible";
        break;
      case 21:
        toReturn = "Neige modérée";
        break;
      case 22:
        toReturn = "Neige forte";
        break;
  
      case 30:
        toReturn = "Pluie et neige mêlées faibles";
        break;
      case 31:
        toReturn = "Pluie et neige mêlées modérées";
        break;
      case 32:
        toReturn = "Pluie et neige mêlées fortes";
        break;
  
      case 40:
        toReturn = "Averses de pluie locales et faibles";
        break;
      case 41:
        toReturn = "Averses de pluie locales";
        break;
      case 42:
        toReturn = "Averses locales et fortes";
        break;
      case 43:
        toReturn = "Averses de pluie faibles";
        break;
      case 44:
        toReturn = "Averses de pluie";
        break;
      case 45:
        toReturn = "Averses de pluie fortes";
        break;
      case 46:
        toReturn = "Averses de pluie faibles et fréquentes";
        break;
      case 47:
        toReturn = "Averses de pluie fréquentes";
        break;
      case 48:
        toReturn = "Averses de pluie fortes et fréquentes";
        break;
  
      case 60:
        toReturn = "Averses de neige localisées et faibles";
        break;
      case 61:
        toReturn = "Averses de neige localisées";
        break;
      case 62:
        toReturn = "Averses de neige localisées et fortes";
        break;
      case 63:
        toReturn = "Averses de neige faibles";
        break;
      case 64:
        toReturn = "Averses de neige";
        break;
      case 65:
        toReturn = "Averses de neige fortes";
        break;
      case 66:
        toReturn = "Averses de neige faibles et fréquentes";
        break;
      case 67:
        toReturn = "Averses de neige fréquentes";
        break;
      case 68:
        toReturn = "Averses de neige fortes et fréquentes";
        break;
  
      case 70:
        toReturn = "Averses de pluie et neige mêlées localisées et faibles";
        break;
      case 71:
        toReturn = "Averses de pluie et neige mêlées localisées";
        break;
      case 72:
        toReturn = "Averses de pluie et neige mêlées localisées et fortes";
        break;
      case 73:
        toReturn = "Averses de pluie et neige mêlées faibles";
        break;
      case 74:
        toReturn = "Averses de pluie et neige mêlées";
        break;
      case 75:
        toReturn = "Averses de pluie et neige mêlées fortes";
        break;
      case 76:
        toReturn = "Averses de pluie et neige mêlées faibles et nombreuses";
        break;
      case 77:
        toReturn = "Averses de pluie et neige mêlées fréquentes";
        break;
      case 78:
        toReturn = "Averses de pluie et neige mêlées fortes et fréquentes";
        break;
  
      case 100:
        toReturn = "Orages faibles et locaux";
        break;
      case 101:
        toReturn = "Orages locaux";
        break;
      case 102:
        toReturn = "Orages forts et locaux";
        break;
      case 103:
        toReturn = "Orages faibles";
        break;
      case 104:
        toReturn = "Orages";
        break;
      case 105:
        toReturn = "Orages forts";
        break;
      case 106:
        toReturn = "Orages faibles et fréquents";
        break;
      case 107:
        toReturn = "Orages fréquents";
        break;
      case 108:
        toReturn = "Orages forts et fréquents";
        break;
  
      case 120:
        toReturn = "Orages faibles et locaux de neige ou grésil";
        break;
      case 121:
        toReturn = "Orages locaux de neige ou grésil";
        break;
      case 122:
        toReturn = "Orages forts et locaux de neige ou grésil";
        break;
      case 123:
        toReturn = "Orages faibles de neige ou grésil";
        break;
      case 124:
        toReturn = "Orages de neige ou grésil";
        break;
      case 125:
        toReturn = "Orages forts de neige ou grésil";
        break;
      case 126:
        toReturn = "Orages faibles et fréquents de neige ou grésil";
        break;
      case 127:
        toReturn = "Orages fréquents de neige ou grésil";
        break;
      case 128:
        toReturn = "Orages forts et fréquents de neige ou grésil";
        break;
  
      case 130:
        toReturn = "Orages faibles et locaux de pluie et neige mêlées ou grésil";
        break;
      case 131:
        toReturn = "Orages locaux de pluie et neige mêlées ou grésil";
        break;
      case 132:
        toReturn = "Orages fort et locaux de pluie et neige mêlées ou grésil";
        break;
      case 133:
        toReturn = "Orages faibles de pluie et neige mêlées ou grésil";
        break;
      case 134:
        toReturn = "Orages de pluie et neige mêlées ou grésil";
        break;
      case 135:
        toReturn = "Orages forts de pluie et neige mêlées ou grésil";
        break;
      case 136:
        toReturn = "Orages faibles et fréquents de pluie et neige mêlées ou grésil";
        break;
      case 137:
        toReturn = "Orages fréquents de pluie et neige mêlées ou grésil";
        break;
      case 138:
        toReturn = "Orages forts et fréquents de pluie et neige mêlées ou grésil";
        break;
  
      case 140:
        toReturn = "Pluies orageuses";
        break;
      case 141:
        toReturn = "Pluie et neige mêlées à caractère orageux";
        break;
      case 142:
        toReturn = "Neige à caractère orageux";
        break;
  
      case 210:
        toReturn = "Pluie faible intermittente";
        break;
      case 211:
        toReturn = "Pluie modérée intermittente";
        break;
      case 212:
        toReturn = "Pluie forte intermittente";
        break;
  
      case 220:
        toReturn = "Neige faible intermittente";
        break;
      case 221:
        toReturn = "Neige modérée intermittente";
        break;
      case 222:
        toReturn = "Neige forte intermittente";
        break;
  
      case 230:
        toReturn = "Pluie et neige mêlées faibles";
        break;
      case 231:
        toReturn = "Pluie et neige mêlées";
        break;
      case 232:
        toReturn = "Pluie et neige mêlées fortes";
        break;
  
      case 235:
        toReturn = "Averses de grêle";
        break;
    }
    return toReturn;
  };
  
  /**
   * Fonction qui renvoie la class html/css de l'icone du temps correspondant au code passé en paramètre.
   * @param {int} code : code du temps 'sensible' prévu.
   * @returns {string} : class de l'icone.
   */
  static getWeatherIconClassByCode = (code) => {
    let toReturn = "";
    if(code === 0) {
      toReturn = "wi-day-sunny";
    }
    else if(code === 1 || code === 2 || code === 3) {
      toReturn = "wi-cloud";
    }
    else if(code === 4 || code === 5) {
      toReturn = "wi-cloudy";
    }
    else if(code === 6 || code === 7) {
      toReturn = "wi-fog";
    }
    else if(code >= 10 && code <= 16) {
      toReturn = "wi-rain";
    }
    else if(code >= 20 && code <= 22) {
      toReturn = "wi-snow";
    }
    else if(code >= 30 && code <= 32) {
      toReturn = "wi-rain-mix";
    }
    else if(code >= 40 && code <= 48) {
      toReturn = "wi-showers";
    }
    else if(code >= 60 && code <= 68) {
      toReturn = "wi-snow";
    }
    else if(code >= 70 && code <= 78) {
      toReturn = "wi-rain-mix";
    }
    else if(code >= 100 && code <= 108) {
      toReturn = "wi-storm-showers";
    }
    else if(code >= 120 && code <= 128) {
      toReturn = "wi-thunderstorm";
    }
    else if(code >= 130 && code <= 138) {
      toReturn = "wi-thunderstorm";
    }
    else if(code >= 140 && code <= 142) {
      toReturn = "wi-thunderstorm";
    }
    else if(code >= 210 && code <= 212) {
      toReturn = "wi-sprinkle";
    }
    else if(code >= 220 && code <= 222) {
      toReturn = "wi-snow";
    }
    else if(code >= 230 && code <= 232) {
      toReturn = "wi-rain-mix";
    }
    else if(code === 235) {
      toReturn = "wi-hail";
    }
    else {
      toReturn = "wi-meteor"; //
    }
    return toReturn;
  }

    /**
     * Fonction qui renvoie la class html/css pour colorer un element du dom selon la température passée en paramètre.
     * @param {int} temp : température à prendre en compte
     * @returns {string} : class css pour colorer
     */
    static getColorClassByTemp = (temp) => {
        let toReturn = "";
        if(temp < -20) {
            toReturn = "color-temp-0";
        } 
        if(temp >= -20 && temp < -10) {
            toReturn = "color-temp-1";
        } 
        else if(temp >= -10 && temp < 0) {
            toReturn = "color-temp-2";
        }
        else if(temp >= 0 && temp < 10) {
            toReturn = "color-temp-3";
        }
        else if(temp >= 10 && temp < 20) {
            toReturn = "color-temp-4";
        }
        else if(temp >= 20 && temp < 30) {
            toReturn = "color-temp-5";
        }
        else if(temp >= 30 && temp < 40) {
            toReturn = "color-temp-6";
        }
        else if(temp >= 40) {
            toReturn = "color-temp-";
        }
        return toReturn;
    }

    static getBgColorClassByTemp = (temp) => {
        let toReturn = "";
        if(temp < -20) {
            toReturn = "bg-temp-0";
        } 
        else if(temp >= -20 && temp < -10) {
            toReturn = "bg-temp-1";
        } 
        else if(temp >= -10 && temp < 0) {
            toReturn = "bg-temp-2";
        }
        else if(temp >= 0 && temp < 10) {
            toReturn = "bg-temp-3";
        }
        else if(temp >= 10 && temp < 20) {
            toReturn = "bg-temp-4";
        }
        else if(temp >= 20 && temp < 30) {
            toReturn = "bg-temp-5";
        }
        else if(temp >= 30 && temp < 40) {
            toReturn = "bg-temp-6";
        }
        else if(temp >= 40) {
            toReturn = "bg-temp-7";
        }
        return toReturn;
    }

    /**
     * Fonction qui renvoie la class html/css de l'icone qui montre la direction du vent.
     * @param {int} dir : direction du vent en degré (Nord = 0° ou 360°).
     * @returns {string} : class de l'icone montrant la direction du vent.
     */
    static getWindDirectionClassByDir = (dir) => {
        let toReturn = `from-${dir}-deg`; // ou towards-xxx-deg
        return toReturn;
    }
}