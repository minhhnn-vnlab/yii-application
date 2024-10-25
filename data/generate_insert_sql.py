import csv

def generate_insert_sql(csv_file, sql_file):
    with open(csv_file, 'r') as csvfile, open(sql_file, 'w') as sqlfile:
        reader = csv.DictReader(csvfile)
        sqlfile.write("CREATE TABLE students (\n")
        sqlfile.write("    id SERIAL PRIMARY KEY,\n")
        sqlfile.write("    gender VARCHAR(10),\n")
        sqlfile.write("    race_ethnicity VARCHAR(20),\n")
        sqlfile.write("    parental_level_of_education VARCHAR(30),\n")
        sqlfile.write("    lunch VARCHAR(20),\n")
        sqlfile.write("    test_preparation_course VARCHAR(20),\n")
        sqlfile.write("    math_score INT,\n")
        sqlfile.write("    reading_score INT,\n")
        sqlfile.write("    writing_score INT\n")
        sqlfile.write(");\n\n")

        sqlfile.write("INSERT INTO students VALUES\n")
        for i, row in enumerate(reader):
            values = [
                row['id'],
                f"'{row['gender']}'",
                f"'{row['race/ethnicity']}'",
                f"'{row['parental level of education']}'",
                f"'{row['lunch']}'",
                f"'{row['test preparation course']}'",
                row['math score'],
                row['reading score'],
                row['writing score']
            ]
            sqlfile.write(f"({', '.join(values)})")
            if i < reader.line_num - 2:
                sqlfile.write(",\n")
            else:
                sqlfile.write(";\n")

if __name__ == "__main__":
    csv_file = 'students.csv'
    sql_file = 'seed.sql'
    generate_insert_sql(csv_file, sql_file)