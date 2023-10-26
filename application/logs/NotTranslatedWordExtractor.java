import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

public class NotTranslatedWordExtractor {

	public static void main(String[] args) {
		BufferedReader reader;

		try (FileWriter writer = new FileWriter("NOT-TRANSLATED-WORDS.txt")) {
			reader = new BufferedReader(new FileReader("log-2023-10-01.php"));
			String line = reader.readLine();
			Set<String> words = new HashSet<>();

			while (line != null) {
				if (line.contains("Could not find the language line")) {
					// add them to new translate file
					char[] chars = line.toCharArray();
					boolean start = false;
					boolean end = false;
					StringBuilder sBuilder = new StringBuilder();

					for (int i = 0; i < line.length(); i++) {
						if (chars[i] == '"') {
							if (!start)
								start = true;
							else
								end = true;
						}
						if (start)
							sBuilder.append(chars[i]);
						if (end)
							break;
					}
					words.add("$lang[" + sBuilder.toString() + "] = \"\";");
				}
				// read next line
				line = reader.readLine();
			}

			for (String word : words) {
				// write extracted words to language list format that used in erp
				writer.write(word);
				writer.write('\n');
			}

			reader.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

}