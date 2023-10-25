Automative skill testing system with integrated chatGpt. Checks answers in real time, assigns scores, calculate them. Time for answers is limited by a timer.(Connection to the database is required and the model needs to be configured depending on the table name and its fields) Link: http://skill.podudali.beget.tech/auth/

DB: 
Table('answer'), fields('id', 'name', 'surname', 'answer', 'question', 'level', 'rating', 'date');
Table('easy_questions'), fields('id', 'question', 'level');
Table('gpt'), fields('id', 'gpt');
Table('hard_questions'), fields('id', 'question', 'level');
Table('normal_questions'), fields('id', 'question', 'level');
Table('persons'), fields('id', 'name', 'surname', 'points', 'date');
Table('questions'), fields('id', 'question', 'level');

