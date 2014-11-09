package fishgenerator.util;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.net.URISyntaxException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.InvalidPropertiesFormatException;
import java.util.Properties;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public final class DbConnector {
	private static final Logger LOGGER = LoggerFactory
			.getLogger(DbConnector.class);

	private static Properties prop;

	private static String driverClass;
	private static String urlString;
	private static String userName;
	private static String password;
	private static String dumpCommand;

	static {
		try {
			setProperties("db.properties");
		} catch (FileNotFoundException e) {
			LOGGER.error("Cannot find db.properties file.", e);
		} catch (InvalidPropertiesFormatException e) {
			LOGGER.error("db.properties format is invalid.", e);
		} catch (IOException e) {
			LOGGER.error("Error while reading db.properties file.", e);
		} catch (URISyntaxException e) {
			LOGGER.error("Bad file db.properties URI.", e);
		}
	}

	private static void setProperties(String fileName)
			throws FileNotFoundException, IOException,
			InvalidPropertiesFormatException, URISyntaxException {
		prop = new Properties();
		final FileInputStream fileInputStream = new FileInputStream(new File(
				DbConnector.class.getClassLoader().getResource(fileName)
						.toURI()));
		prop.load(fileInputStream);

		driverClass = prop.getProperty("db.driver_class");
		LOGGER.debug("db.driver_class={}", driverClass);

		urlString = prop.getProperty("db.url");
		LOGGER.debug("db.url={}", urlString);

		userName = prop.getProperty("db.username");
		LOGGER.debug("db.username={}", userName);

		password = prop.getProperty("db.password");
		LOGGER.debug("db.password={}", password);

		dumpCommand = prop.getProperty("db.dump.command");
		LOGGER.debug("db.dump.command={}", dumpCommand);

	}

	public static Connection getConnection() throws SQLException {
		final Properties connectionProps = new Properties();
		connectionProps.put("user", userName);
		connectionProps.put("password", password);

		final Connection connection = DriverManager.getConnection(urlString,
				connectionProps);

		LOGGER.debug("Connected to database");
		return connection;
	}

	public static Connection getConnection(final String database)
			throws SQLException {
		final Connection connection = getConnection();
		connection.setCatalog(database);

		LOGGER.debug("Connected to database: {}", database);
		return connection;
	}

	public static void closeConnection(final Connection connection) {
		try {
			if (connection != null) {
				connection.close();
			}
		} catch (SQLException sqle) {
			printSQLException(sqle);
		}
	}

	public static void export(String dbName) {

		Runtime rt = Runtime.getRuntime();

		PrintStream ps;

		try {
			File file = new File(dbName + ".sql");
			Process child = rt.exec(dumpCommand + " " + dbName);
			ps = new PrintStream(file);
			InputStream in = child.getInputStream();
			int ch;
			while ((ch = in.read()) != -1) {
				ps.write(ch);
			}

			BufferedReader err = new BufferedReader(new InputStreamReader(
					child.getErrorStream()));
			StringBuilder errorBuilder = new StringBuilder();
			String line;
			while ((line = err.readLine()) != null) {
				errorBuilder.append(line);
			}
			if (errorBuilder.length() != 0) {
				LOGGER.error(errorBuilder.toString());
			}
		} catch (Exception e) {
			LOGGER.error("Error while generating database dump", e);
		}
	}

	private static void printSQLException(SQLException ex) {
		while (ex != null) {
			LOGGER.error("SQLState: {}", ex.getSQLState());
			LOGGER.error("Error Code: {}", ex.getErrorCode());
			LOGGER.error("Message: {}", ex.getMessage());
			Throwable t = ex.getCause();
			while (t != null) {
				LOGGER.error("Cause: {}", t);
				t = t.getCause();
			}
			ex = ex.getNextException();
		}
	}
}