import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import java.util.Objects;

import org.apache.commons.codec.binary.Base64;
import org.apache.commons.codec.digest.DigestUtils;
import org.apache.commons.collections4.CollectionUtils;
import org.apache.commons.collections4.Transformer;
import org.apache.commons.io.Charsets;
import org.apache.commons.lang3.StringUtils;
import org.apache.http.NameValuePair;

public class FutubankUtils {

    /**
     * Calculates signature.
     *
     * @param pairs parameters
     * @param secret secret
     * @return result
     */
    public static String getSign(Collection<NameValuePair> pairs, String secret) {
        Objects.requireNonNull(secret, "secret is null");
        List<NameValuePair> list = new ArrayList<>(Objects.requireNonNull(pairs, "pairs is null"));
        Collections.sort(list, new Comparator<NameValuePair>() {
                @Override
                public int compare(NameValuePair pair1, NameValuePair pair2) {
                    return pair1.getName().compareTo(pair2.getName());
                }
            });
        Collection<String> params = CollectionUtils.collect(list, new Transformer<NameValuePair, String>() {
                @Override
                public String transform(NameValuePair pair) {
                    return pair.getName() + '=' + Base64.encodeBase64String(pair.getValue().getBytes(Charsets.UTF_8));
                }
            });
        return DigestUtils.sha1Hex(secret + DigestUtils.sha1Hex(secret + StringUtils.join(params, '&')).toLowerCase());
    }
}
