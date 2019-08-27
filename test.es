

GET /testfer/_mappings


GET /testfer/_search


DELETE /testfer

PUT /testfer/

PUT /testfer/_mapping/tipo
{
                    "properties": {
                    "ID": {
                        "type": "long"
                    },
                    "clienteid": {
                        "type": "long"
                    },
                    "detalle": {
                        "properties": {
                            "cantidad": {
                                "type": "long"
                            },
                            "producto": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "subtotal": {
                                "type": "long"
                            }
                        }
                    },
                    "fecha": {
                        "type": "date"
                        
                    },
                    "nombre": {
                        "type": "text",
                        "fields": {
                            "keyword": {
                                "type": "keyword",
                                "ignore_above": 256
                            }
                        }
                    },
                    "telefonos": {
                        "properties": {
                            "numero": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            }
                        }
                    }
                }
}


