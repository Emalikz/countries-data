import json
import unicodedata


def normalizar(texto):
    # Elimina acentos y convierte a minúsculas
    return ''.join(
        c for c in unicodedata.normalize('NFD', texto)
        if unicodedata.category(c) != 'Mn'
    ).lower()

def read_file(data_path):
    with open(data_path) as f:
        data = json.load(f)
    return data

original_data = read_file('database/data/data.json')
departamentos_data = read_file('database/data/departamento.json')
municipios_data = read_file('database/data/municipio.json')

def find_internal_code(departament_name):
    for item in departamentos_data:
        if normalizar(item['Nombre'].lower()) == normalizar(departament_name.lower()):
            return item['Codigo']
        
def find_postal_code(municipio_name):
    for item in municipios_data:
        if normalizar(item['Nombre'].lower()) == normalizar(municipio_name.lower()):
            return item['Codigo']


def make_new_data(data):
    for country_index,item in enumerate(data):

        if item['id'] == 48:
            for state_index, state in enumerate(item['states']):
                if not hasattr(state, 'internal_code'):
                    state.update({'internal_code': find_internal_code(state['name'])})
                if state['internal_code'] == '' or state['internal_code'] is None:
                    state['internal_code'] = find_internal_code(state['name'])

                for city_index, city in enumerate(state['cities']):                    
                    if not hasattr(city, 'postal_code'):
                        city.update({'postal_code': find_postal_code(city['name'])})
                    if city['postal_code'] == '' or city['postal_code'] is None:
                        city['postal_code'] = find_postal_code(city['name'])
                        
    with open('database/data/new_data.json', 'w', encoding='utf-8') as f:
        json.dump(original_data, f, indent=4, ensure_ascii=False)
                
                

                
make_new_data(original_data)